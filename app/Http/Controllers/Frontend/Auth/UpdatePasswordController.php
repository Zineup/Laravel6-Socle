<?php

namespace App\Http\Controllers\Frontend\Auth;

use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Frontend\Auth\UserRepository;
use App\Http\Requests\Frontend\User\UpdatePasswordRequest;

/**
 * Class UpdatePasswordController.
 */
class UpdatePasswordController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ChangePasswordController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdatePasswordRequest $request
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdatePasswordRequest $userRequest)
    {
        $access_token = $this->getAccessToken();
        $id = Auth::user()->sub;
        
        $client = new Client(['headers' => [
            'Authorization' => 'Bearer '. $access_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
            ]]);

        $url = 'http://localhost:8080/auth/admin/realms/Demo-Realm/users/'. $id;

        $request = $client->request('PUT', $url, 
        [
            'json' => [

                'credentials' => 
                [[
                    'type' => 'password',
                    'value' => $userRequest['password'],
                    'temporary' => false
                ]]
            ] 
        ]);

        $response = $request->getStatusCode();

        if($response != 204){

            return redirect()->route('frontend.user.account')->withFlashDanger('User password not updated');
        }

        return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.password_updated'));
    }
}
