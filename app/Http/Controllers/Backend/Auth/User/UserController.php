<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\UserRepository;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Http\Requests\Backend\Auth\User\StoreUserRequest;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserRequest;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function init()
    {
        $response = $this->userRepository->getAllUsers();

        $users = [];
        foreach ($response as $user) {
            $user_object = new User();

            $user_object->uid = $user->id;
            $user_object->last_name = isset($user->lastName) ? $user->lastName : '';
            $user_object->first_name = isset($user->firstName) ? $user->firstName : '';
            $user_object->email = isset($user->email) ? $user->email : '';
            $user_object->createdTimestamp = $user->createdTimestamp;
            $user_object->roles = $this->userRepository->getUserRoles($user->id);
            $user_object->confirmed = $user->emailVerified;

            array_push($users, $user_object);
        }

        return $users;
    }

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request)
    {
        if (session('users') == null || ! strpos($request->session()->get('flash_success'), 'created')) {
            $request->session()->put('users', $this->init());
        }

        if (strpos($request->session()->get('flash_success'), 'created')) {
            $index = count(session('users')) + 1;
            Session::put('users.'.$index, $request->session()->get('user'));
        }

        $users = session('users');

        return view('backend.auth.user.index')->withUsers($users);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request /*, RoleRepository $roleRepository, PermissionRepository $permissionRepository*/)
    {
        $roles = $this->userRepository->getAllRoles();

        return view('backend.auth.user.create')
            ->withRoles($roles);
    }

    /**
     * @param StoreUserRequest $request
     *
     * @throws \Throwable
     * @return mixed
     */
    public function store(StoreUserRequest $userRequest)
    {
        $data = $userRequest->only(
            'username',
            'first_name',
            'last_name',
            'email',
            'password',
            'active',
            'confirmed',
            'roles',
        );

        $response_header = $this->userRepository->create($data);

        // Get user id from header
        $array_header = explode('/', $response_header);
        $user_id = end($array_header);

        // Role mapping
        $this->userRepository->addRemoveUserRoles('POST', $userRequest['roles'], $user_id);

        //Create User
        $user = $this->createUserObject($user_id, $data);

        return redirect()->route('admin.auth.user.index')
            ->withFlashSuccess(__('alerts.backend.users.created'))
            ->withUser($user);
    }

    /**
     * @return mixed
     */
    public function show(ManageUserRequest $request)
    {
        $user = $this->userRepository->getUser($request['uid']);

        return view('backend.auth.user.show')
            ->withUser($user);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param User                 $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, /*RoleRepository $roleRepository, PermissionRepository $permissionRepository,*/ User $user)
    {
        $user = $this->userRepository->getUser($request['uid']);
        $user->roles = $this->userRepository->getUserRoles($user->uid);
        $roles = $this->userRepository->getAllRoles();

        return view('backend.auth.user.edit')
            ->withUser($user)
            ->withRoles($roles);
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function update(UpdateUserRequest $userRequest, User $user)
    {
        $response = $this->userRepository->update($userRequest['uid'], $userRequest->only(
            'username',
            'first_name',
            'last_name',
            'email',
        ));

        $this->userRepository->updateUserRoles([
            'uid' => $userRequest['uid'],
            'roles' => $userRequest['roles'],
            'response' => $response,
        ]);

        $user = $this->userRepository->getUser($userRequest['uid']);
        $user->roles = $this->userRepository->getUserRoles($userRequest['uid']);

        return redirect()->route('admin.auth.user.index')
            ->withFlashSuccess(__('alerts.backend.users.updated'))
            ->withUser($user);
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function destroy(ManageUserRequest $userRequest)
    {
        $this->userRepository->delete($userRequest['uid']);

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.deleted'));
    }

    /**
     * @return mixed
     */
    public function createUserObject($user_id, array $data)
    {
        $user = new User();
        $user->uid = $user_id;
        $user->last_name = $data['last_name'];
        $user->first_name = $data['first_name'];
        $user->email = $data['email'];
        $user->createdTimestamp = now()->diffForHumans();
        $user->roles = $this->userRepository->formatRoles($data['roles']);
        $user->confirmed = $data['confirmed'];

        return $user;
    }
}
