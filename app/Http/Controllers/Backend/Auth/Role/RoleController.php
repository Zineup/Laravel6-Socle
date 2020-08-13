<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use GuzzleHttp\Client;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Events\Backend\Auth\Role\RoleDeleted;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Http\Requests\Backend\Auth\Role\StoreRoleRequest;
use App\Http\Requests\Backend\Auth\Role\ManageRoleRequest;
use App\Http\Requests\Backend\Auth\Role\UpdateRoleRequest;

/**
 * Class RoleController.
 */
class RoleController extends Controller
{

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function index(ManageRoleRequest $request)
    {

        $access_token = $this->getAccessToken();
        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token,
        ]]);

        //***************** Get All Roles ***************** */

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/roles';

        $request = $client->get($url);

        $response = json_decode($request->getBody());

        $roles = [];

        foreach($response as $role){

            $role_object = new Role();
            $role_object->uid = $role->id;
            $role_object->name = $role->name ;
            $role_object->description = $role->description ;
            $role_object->composite = $role->composite ;

            //get number of user for each role

            $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/roles/'. $role_object->name. '/users' ;
            $request = $client->get($url);
            $users_response = json_decode($request->getBody());
            
            $role_object->nb_users = sizeof($users_response);
            array_push($roles, $role_object);
        }

        return view('backend.auth.role.index')
            ->withRoles($roles);
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
    public function store(StoreRoleRequest $request)
    {
        $this->roleRepository->create($request->only('name', 'associated-permissions', 'permissions', 'sort'));

        return redirect()->route('admin.auth.role.index')->withFlashSuccess(__('alerts.backend.roles.created'));
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role              $role
     *
     * @return mixed
     */
    public function edit(ManageRoleRequest $request, Role $role)
    {
        if ($role->isAdmin()) {
            return redirect()->route('admin.auth.role.index')->withFlashDanger('You can not edit the administrator role.');
        }

        return view('backend.auth.role.edit')
            ->withRole($role)
            ->withRolePermissions($role->permissions->pluck('name')->all())
            ->withPermissions($this->permissionRepository->get());
    }

    /**
     * @param  UpdateRoleRequest  $request
     * @param  Role  $role
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->roleRepository->update($role, $request->only('name', 'permissions'));

        return redirect()->route('admin.auth.role.index')->withFlashSuccess(__('alerts.backend.roles.updated'));
    }

    public function destroy(ManageRoleRequest $roleRequest)
    {
        $access_token = $this->getAccessToken();
        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token,
        ]]);

        //***************** Get All Roles ***************** */

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/roles-by-id/'. $roleRequest['uid'];

        $request = $client->delete($url);

        $response = json_decode($request->getStatusCode());

        return redirect()->route('admin.auth.role.index')->withFlashSuccess(__('alerts.backend.roles.deleted'));
    }
}
