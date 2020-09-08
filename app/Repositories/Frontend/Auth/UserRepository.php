<?php

namespace App\Repositories\Frontend\Auth;

use App\Models\Auth\User;
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
            'Authorization' => 'Bearer '.$this->access_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]]);
    }

    public function getAccessToken()
    {
        $client = new Client();

        $url = config('keycloak-web.base_url'). '/realms/master/protocol/openid-connect/token';

        $my_body = [
            'username' => 'admin',
            'password' => 'admin',
            'grant_type' => 'password',
            'client_id' => 'admin-cli',
        ];

        $request = $client->post(
            $url,
            ['form_params' => $my_body]
        );

        $response = json_decode($request->getBody())->access_token;

        return $response;
    }

    public function updateUser($user_id, array $data)
    {
        $url = config('keycloak-web.base_url'). '/admin/realms/'. config('keycloak-web.realm'). '/users/'. $user_id;

        $request = $this->client->request('PUT', $url, 
        [
            'json' => [
                'firstName' => $data['first_name'],
                'lastName' => $data['last_name'],
                'email' => $data['email'],
            ]
        ]);

        return $request->getStatusCode();
    }

    public function updatePassword(array $data)
    {
        $url = config('keycloak-web.base_url'). '/admin/realms/'. config('keycloak-web.realm'). '/users/'. $data['id'];

        $request = $this->client->request('PUT', $url, 
        [
            'json' => [

                'credentials' => 
                [[
                    'type' => 'password',
                    'value' => $data['password'],
                    'temporary' => false
                ]]
            ] 
        ]);

        return $request->getStatusCode();

    }
    
}
