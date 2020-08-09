<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
}
