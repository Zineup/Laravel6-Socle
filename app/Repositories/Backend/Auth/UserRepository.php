<?php

namespace App\Repositories\Backend\Auth;


use App\Exceptions\GeneralException;
use App\Models\User;
use App\Repositories\BaseRepository;
use GuzzleHttp\Client;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    private $access_token;
    private $client;
    /**
     * UserRepository constructor.
     *
     * @param  User  $model
     */

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->access_token = $this->getAccessToken();
        $this->client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $this->access_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]]);
    }

    public function getAccessToken(){

        $client = new \GuzzleHttp\Client();

        $url = 'http://localhost:8080/auth/realms/master/protocol/openid-connect/token';

        $my_body = [
            "username" => "admin",
            "password" => "admin",
            "grant_type" => "password",
            "client_id" => "admin-cli"
        ];

        $request = $client->post(
            $url, 
            [ 'form_params' => $my_body ]
        );

        $response = json_decode($request->getBody())->access_token;

        return $response;

    }

    /**
     
     */
    public function getAllUsers()
    {
        
        //******************* Get Users **************/

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users';

        $request = $this->client->request('GET', $url);

        $response = json_decode($request->getBody());

        return $response;
    }

    /**
     * @param array $data
     *
     * @throws \Exception
     * @throws \Throwable
     * @return mixed
     */
    public function create(array $data)
    {
        //*************** Create a new user **************** */

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users';

        $request = $this->client->request('POST', $url, 
        [
            'json' => [
                'username' => $data['username'],
                'lastName' => $data['last_name'],
                'firstName' => $data['first_name'],
                'email' => $data['email'],
                'emailVerified' => $data['confirmed'] ? true : false ,
                'enabled' => true,

                'credentials' => 
                [[
                    'type' => 'password',
                    'value' => $data['password'],
                    'temporary' => false
                ]]
            ] 
        ]);

        $response_header = $request->getHeader('Location')[0];
        return $response_header;
    }

    
    /**
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Exception
     * @throws \Throwable
     * @return mixed
     */
    public function update($user_id, array $data)
    {

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/'. $user_id;

        $request = $this->client->request('PUT', $url, 
        [
            'json' => [
                'username' => $data['username'],
                'firstName' => $data['first_name'],
                'lastName' => $data['last_name'],
                'email' => $data['email'],
            ]
        ]
        );

        $response = $request->getStatusCode();
        return $response;
    }

    /**
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Exception
     * @throws \Throwable
     * @return mixed
     */
    public function updateUserRoles(array $data)
    {
    //******************* Get Roles to add and Roles to remove ******************** */

        $user_roles = $this->getUserRoles($data['uid']);
        $user_roles = json_decode(json_encode ($user_roles), FALSE);

        $test = [];
        foreach ($user_roles as $role) {
            array_push($test, $role->id.'/'.$role->name);
        }

        $roles_to_add = [];
        $roles_to_remove = [];

        foreach ($data['roles'] as $role) 
        {
            if(!in_array($role, $test))
            {
                array_push($roles_to_add, $role );
            }
        }

        foreach ($test as $role) 
        {
            if(!in_array($role, $data['roles']))
            {
                array_push($roles_to_remove, $role );
            }
        }

    //************* Add Roles ************ */

        if(sizeof($roles_to_add) > 0 AND $data['response'] == 204 )
        {
            return $this->addRemoveUserRoles('POST', $roles_to_add, $data['uid']);
        }

        //************* Remove Roles ************ */

        if(sizeof($roles_to_remove) > 0 AND $data['response'] == 204)
        {
            return $this->addRemoveUserRoles('DELETE', $roles_to_remove, $data['uid']);
        }
    }

     /**
     * @param string $user_id
     *
     * @throws GeneralException
     * @throws \Exception
     * @throws \Throwable
     * @return mixed
     */
    public function delete($user_id)
    {

        //******************* DELETE User **************/

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/'. $user_id;

        $request = $this->client->request('DELETE', $url);

        return $request->getStatusCode();
    }


    /**
     * @param String  $uid
     *
     * @return User
     */

    public function getUser(String $uid)
    {        

        //******************* Get User **************/

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/'. $uid;

        $request = $this->client->request('GET', $url);

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

    public function getUserRoles(String $uid)
    {        

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/' .$uid .'/role-mappings';

        $request = $this->client->request('GET', $url);
   
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

    public function getAllRoles()
    { 
        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/roles';

        $request = $this->client->get( $url);

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


    public function addRemoveUserRoles(String $verb, $roles, $user_id)
    {

        $json_array = $this->formatRoles($roles);

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/'. $user_id.'/role-mappings/realm';

        $request = $this->client->request($verb, $url, 
        [
            'json' => $json_array
        ]);
        
        $response = $request->getStatusCode();

        return $response;

    }

    public function formatRoles($roles){

        $json_array = [];

        foreach($roles as $role){

            $array = explode("/", $role);
            $role_id = $array[0];
            $role_name = $array[1];

            array_push($json_array,
                [
                    'id' => $role_id,
                    'name' =>$role_name
                ] 
            );
        }

        return $json_array;
    }

    
}
