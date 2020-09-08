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
        $user_id = Auth::user()->sub;

        $response = $this->userRepository->updateUser($user_id, $request->only(
            'first_name',
            'last_name',
            'email'
        ));

        return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    }
}
