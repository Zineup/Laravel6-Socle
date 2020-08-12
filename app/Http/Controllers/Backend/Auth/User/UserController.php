<?php

namespace App\Http\Controllers\Backend\Auth\User;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\User;
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
use App\Models\Auth\User as AuthUser;

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

        //******************* Get Users **************/

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users';

        $request = $client->request('GET', $url);

        $response = json_decode($request->getBody());

        $users = [];

        foreach($response as $user){

            $user_object = new User();

            $user_object->uid = $user->id;
            $user_object->last_name = isset($user->lastName) ? $user->lastName : "";
            $user_object->first_name = isset($user->firstName) ? $user->firstName : "";
            $user_object->email = isset($user->email) ? $user->email : "";
            $user_object->createdTimestamp = $user->createdTimestamp;
            $user_object->roles = $this->getUserRoles($user->id, $access_token);
            $user_object->confirmed = $user->emailVerified;

            array_push($users, $user_object);
        }

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
        $access_token = $this->getAccessToken();              

        $roles = $this->getAllRoles($access_token);

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
                'username' => $userRequest['username'],
                'lastName' => $userRequest['last_name'],
                'firstName' => $userRequest['first_name'],
                'email' => $userRequest['email'],
                'emailVerified' => $userRequest['confirmed'] ? true : false ,
                'enabled' => true,

                'credentials' => 
                [[
                    'type' => 'password',
                    'value' => $userRequest['password'],
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

            $response = $this->addRemoveUserRoles($access_token, $client, 'POST', $userRequest['roles'], $user_id);

            if($response != 204){
                return redirect()->route('admin.auth.user.index')->withFlashDanger('User not created !');
            }
        }

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.created'));
    }

    /**        
     *
     * @return mixed
     */
    public function show(ManageUserRequest $request)
    {
        $user = $this->getUser($request['uid']);        

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
        $access_token = $this->getAccessToken();
        $user = $this->getUser($request['uid']);
        $user->roles = $this->getUserRoles($user->uid, $access_token);
        $roles = $this->getAllRoles($access_token);

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
        //dd($request['roles']);
        $access_token = $this->getAccessToken();
        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
            ]]);

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/'. $userRequest['uid'];

        $request = $client->request('PUT', $url, 
        [
            'json' => [
                'username' => $userRequest['username'],
                'firstName' => $userRequest['first_name'],
                'lastName' => $userRequest['last_name'],
                'email' => $userRequest['email'],
            ]
        ]
        );

        $response = $request->getStatusCode();

        //******************* Get Roles to add and Roles to remove ******************** */

        $user_roles = $this->getUserRoles($userRequest['uid'], $access_token);
        $user_roles = json_decode(json_encode ($user_roles), FALSE);

        $test = [];
        foreach ($user_roles as $role) {
            array_push($test, $role->id.'/'.$role->name);
        }

        $roles_to_add = [];
        $roles_to_remove = [];

        foreach ($userRequest['roles'] as $role) 
        {
            if(!in_array($role, $test))
            {
                array_push($roles_to_add, $role );
            }
        }

        foreach ($test as $role) 
        {
            if(!in_array($role, $userRequest['roles']))
            {
                array_push($roles_to_remove, $role );
            }
        }

        //************* Add Roles ************ */

        if(sizeof($roles_to_add) > 0 AND $response == 204 )
        {
            $response = $this->addRemoveUserRoles($access_token, $client, 'POST', $roles_to_add, $userRequest['uid']);

            if($response != 204){
                return redirect()->route('admin.auth.user.index')->withFlashSuccess('User not updated !');
            }
        }

        //************* Remove Roles ************ */

        if(sizeof($roles_to_remove) > 0 AND $response == 204)
        {
            $response = $this->addRemoveUserRoles($access_token, $client, 'DELETE', $roles_to_remove, $userRequest['uid']);
        
            if($response != 204){
                return redirect()->route('admin.auth.user.index')->withFlashSuccess('User not updated !');
            }
        }


        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.updated'));
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function destroy(ManageUserRequest $userRequest)
    {
        $access_token = $this->getAccessToken();
        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token,
        ]]);

        //******************* DELETE User **************/

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/'. $userRequest['uid'];

        $request = $client->request('DELETE', $url);

        $response = $request->getStatusCode();

        if($response != 204 ){

            return redirect()->route('admin.auth.user.index')->withFlashDanger('User not deleted !');
        }

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.deleted'));
    }

    /**
     * @param String  $uid
     *
     * @return User
     */

    public function getUser(String $uid)
    {
        $access_token = $this->getAccessToken();
        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token,
        ]]);

        //******************* Get User **************/

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/'. $uid;

        $request = $client->request('GET', $url);

        $response = json_decode($request->getBody());

        $user = new User();

        $user->uid = $response->id;
        $user->username = $response->username;
        $user->last_name = isset($response->lastName) ? $response->lastName : "";
        $user->first_name = isset($response->firstName) ? $response->firstName : "";
        $user->email =  isset($response->email) ? $response->email : "";
        $user->confirmed = $response->emailVerified;
        $user->createdTimestamp =  $response->createdTimestamp;

        return $user;

    }       

    public function getUserRoles(String $uid, String $access_token)
    {        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token,
        ]]);

        //******************* Get User **************/

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/' .$uid .'/role-mappings';

        $request = $client->request('GET', $url);
   
        $response = json_decode($request->getBody())->realmMappings;
        
        $roles = [];        
        
        foreach ($response as $role) {

            if($role->name != 'offline_access' AND $role->name != 'uma_authorization')
            {              
                $role_element['id'] = $role->id;
                $role_element['name'] = $role->name;
                array_push($roles, $role_element);                
            }
                       
        }          

        return $roles;
    }

    public function getAllRoles(String $access_token)
    {                
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

        $roles = json_decode(json_encode ($roles), FALSE);

        return $roles;
    }


    public function addRemoveUserRoles(String $access_token, $client, String $verb, $roles, $user_id)
    {
        $json_array = [];

        foreach($roles as $role){

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

        $request = $client->request($verb, $url, 
        [
            'json' => $json_array
        ]);
        
        $response = $request->getStatusCode();

        return $response;

    }

    
}
