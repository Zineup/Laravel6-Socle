<?php

namespace App\Http\Controllers\Backend\Auth\User;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Auth\User;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Events\Backend\Auth\User\UserDeleted;
use Vizir\KeycloakWebGuard\Models\KeycloakUser;
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
        $access_token = $this->getAccessToken();
        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token,
            ]]);

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users';

        $request = $client->request('GET', $url);

        $users = json_decode($request->getBody());

        foreach($users as $user){

            //Getting User Role       

            $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/' .$user->id .'/role-mappings';

            $request = $client->request('GET', $url);
       
            $roles = json_decode($request->getBody())->realmMappings;
            
            $user->roles = [];

            foreach ($roles as $role) {
               
                if($role->name != 'offline_access' AND $role->name != 'uma_authorization'){
                    
                    array_push($user->roles, $role->name);
                }
            }

            if(!isset($user->lastName)){
                $user->lastName = '';
            }

            if(!isset($user->firstName)){
                $user->firstName = '';
            }

            if(!isset($user->username)){
                $user->username = '';
            } 

            if(!isset($user->email)){
                $user->email = '';
            }

            if(isset($user->createdTimestamp)){
                $timestamp = substr($user->createdTimestamp, 0, -3);
                $user->createdTimestamp = Carbon::createFromTimestamp($timestamp);
            }        
        }

        
        //dd($users);

        return view('backend.auth.user.index')->withUsers($users);
            //->withUsers($this->userRepository->getActivePaginated(25, 'id', 'asc'));
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
        //********************* GET ROLES ******************* */

        $access_token = $this->getAccessToken();
        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token
        ]]);

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/roles';

        $request = $client->get( $url);

        $response = json_decode($request->getBody());
        $roles = [];

        foreach ($response as $role) {
               
            if($role->name != 'offline_access' AND $role->name != 'uma_authorization')
            {              
                $role_element['id'] = $role->id;
                $role_element['name'] = $role->name;
                array_push($roles, $role_element);                
            }
        }

        //dd($roles);

        $roles = json_decode (json_encode ($roles), FALSE);

        return view('backend.auth.user.create')
            ->withRoles($roles);
            //  ->withRoles($roleRepository->with('permissions')->get(['id', 'name']))
            // ->withPermissions($permissionRepository->get(['id', 'name']));
    }

    /**
     * @param StoreUserRequest $request
     *
     * @throws \Throwable
     * @return mixed
     */
    public function store(StoreUserRequest $UserRequest)
    {
        $access_token = $this->getAccessToken();
        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]]);

        //*************** Create a new user **************** */

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users';

        $request = $client->request('POST', $url, 
        [
            'json' => [
                'username' => $UserRequest['username'],
                'lastName' => $UserRequest['last_name'],
                'firstName' => $UserRequest['first_name'],
                'email' => $UserRequest['email'],
                'emailVerified' => $UserRequest['confirmed'] ? true : false ,
                'enabled' => true,

                'credentials' => 
                [[
                    'type' => 'password',
                    'value' => $UserRequest['password'],
                    'temporary' => false
                ]]
            ] 
        ]);

        $response_statusCode = $request->getStatusCode();
        $response_header = $request->getHeader('Location')[0];

        $array_header = explode("/", $response_header);

        $user_id = end($array_header);

        //*************** Role Mapping **************** */
        
        if($response_statusCode == 201){

            //Getting user roles
            $json_array = [];

            foreach($UserRequest['roles'] as $role){

                $array = explode("/", $role);
                $role_id = $array[0];
                $role_name = $array[1];

                array_push( $json_array,
                    [
                        'id' => $role_id,
                        'name' =>$role_name
                    ] 
                );
            }

            $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/'. $user_id.'/role-mappings/realm';

            $request = $client->request('POST', $url, 
            [
                'json' => $json_array
            ]);
        }

        $response = $request->getStatusCode();

        if($response != 204){
            return redirect()->route('admin.auth.user.index')->withFlashDanger('User not created !');
        }

        // $this->userRepository->create($request->only(
        //     'first_name',
        //     'last_name',
        //     'email',
        //     'password',
        //     'active',
        //     'confirmed',
        //     'confirmation_email',
        //     'roles',
        //     'permissions'
        // ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.created'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @return mixed
     */
    public function show(ManageUserRequest $request, User $user)
    {
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
    public function edit(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository, User $user)
    {
        return view('backend.auth.user.edit')
            ->withUser($user)
            ->withRoles($roleRepository->get())
            ->withUserRoles($user->roles->pluck('name')->all())
            ->withPermissions($permissionRepository->get(['id', 'name']))
            ->withUserPermissions($user->permissions->pluck('name')->all());
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userRepository->update($user, $request->only(
            'first_name',
            'last_name',
            'email',
            'roles',
            'permissions'
        ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.updated'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @throws \Exception
     * @return mixed
     */
    public function destroy(ManageUserRequest $request, User $user)
    {
        $this->userRepository->deleteById($user->id);

        event(new UserDeleted($user));

        return redirect()->route('admin.auth.user.deleted')->withFlashSuccess(__('alerts.backend.users.deleted'));
    }
}
