<?php

namespace App\Http\Controllers\Frontend\User;

use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Frontend\Auth\UserRepository;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdateProfileRequest $request)
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
                'firstName' => $request['first_name'],
                'lastName' => $request['last_name'],
            ]
        ]
        );

        $response = $request->getStatusCode();

        if($response != 204){

            return redirect()->route('frontend.user.account')->withFlashDanger(__('strings.frontend.user.profile_updated'));
        }

        
        // $output = $this->userRepository->update(
        //     $request->user()->id,
        //     $request->only('first_name', 'last_name', 'email', 'avatar_type', 'avatar_location'),
        //     $request->has('avatar_location') ? $request->file('avatar_location') : false
        // );

        // E-mail address was updated, user has to reconfirm
        // if (is_array($output) && $output['email_changed']) {
        //     auth()->logout();

        //     return redirect()->route('frontend.auth.login')->withFlashInfo(__('strings.frontend.user.email_changed_notice'));
        // }

        return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    }
}
