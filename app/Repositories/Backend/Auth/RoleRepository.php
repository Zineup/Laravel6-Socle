<?php

namespace App\Repositories\Backend\Auth;

use GuzzleHttp\Client;
use App\Models\Role;
use App\Repositories\BaseRepository;

/**
 * Class RoleRepository.
 */
class RoleRepository extends BaseRepository
{
    private $access_token;
    private $client;

    /**
     * RoleRepository constructor.
     *
     * @param  Role  $model
     */
    public function __construct()
    {
        $this->access_token = $this->getAccessToken();
        $this->client = new Client(['headers' => [
            'Authorization' => 'Bearer '.$this->access_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]]);
    }

    public function getAccessToken(){

        $client = new \GuzzleHttp\Client();

        $url = config('keycloak-web.base_url'). '/realms/master/protocol/openid-connect/token';

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

    public function getAllRoles()
    {
        $url = config('keycloak-web.base_url'). '/admin/realms/'. config('keycloak-web.realm'). '/roles';

        $request = $this->client->get($url);

        $response = json_decode($request->getBody());

        $roles = [];

        foreach($response as $role){

            $role_object = new Role();
            $role_object->uid = $role->id;
            $role_object->name = $role->name ;
            $role_object->description = isset($role->description) ? $role->description : '' ;
            $role_object->composite = $role->composite ;

            //get number of user for each role

            $url = config('keycloak-web.base_url'). '/admin/realms/'. config('keycloak-web.realm'). '/roles/'. $role_object->name. '/users' ;
            $request = $this->client->get($url);
            $users_response = json_decode($request->getBody());
            
            $role_object->nb_users = sizeof($users_response);
            array_push($roles, $role_object);
        }

        return $roles;
    }

    
    public function getRole($role_id)
    {
        $url = config('keycloak-web.base_url'). '/admin/realms/'. config('keycloak-web.realm'). '/roles-by-id/'. $role_id;

        $request = $this->client->request('GET', $url);

        $response = json_decode($request->getBody());

        $role = new Role();

        $role->uid = $response->id;
        $role->name = $response->name;
        $role->description = $response->description;
        $role->composite = $response->composite;

        return $role;
    }


    public function create(array $data)
    {
        $url = config('keycloak-web.base_url'). '/admin/realms/'. config('keycloak-web.realm'). '/roles';
        $request = $this->client->post($url, [
            'json' => [
                'name' => $data['name'],
                'description' => $data['description']
            ]
        ]);

        $response = $request->getStatusCode();

        return $response;
    }

    public function deleteRole($role_id)
    {
        $url = config('keycloak-web.base_url'). '/admin/realms/'. config('keycloak-web.realm'). '/roles-by-id/'. $role_id;

        $request = $this->client->delete($url);

        return json_decode($request->getStatusCode());
    }
}
