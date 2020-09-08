<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Http\Requests\Backend\Auth\Role\StoreRoleRequest;
use App\Http\Requests\Backend\Auth\Role\ManageRoleRequest;

/**
 * Class RoleController.
 */
class RoleController extends Controller
{

    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * @param RoleRepository       $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function index(ManageRoleRequest $request)
    {
        $roles = $this->roleRepository->getAllRoles();        

        return view('backend.auth.role.index')
            ->withRoles($roles);
    }

    
    public function show(ManageRoleRequest $roleRequest)
    {
        $role = $this->roleRepository->getRole($roleRequest['uid']);    

        return view('backend.auth.role.show')
            ->withRole($role);
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function create(ManageRoleRequest $request)
    {
        return view('backend.auth.role.create');
    }

    /**
     * @param  StoreRoleRequest  $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreRoleRequest $roleRequest)
    {
        $data = $roleRequest->only(
            'name',
            'description'
        );

        $response = $this->roleRepository->create($data);

        return redirect()->route('admin.auth.role.index')->withFlashSuccess(__('alerts.backend.roles.created'));
    }


    public function destroy(ManageRoleRequest $roleRequest)
    {
       $response = $this->roleRepository->deleteRole($roleRequest['uid']);        

        return redirect()->route('admin.auth.role.index')->withFlashSuccess(__('alerts.backend.roles.deleted'));
    }
}
