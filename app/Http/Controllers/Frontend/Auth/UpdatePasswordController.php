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
        $this->userRepository->updatePassword([
            'id' => Auth::user()->sub,
            'password' => $userRequest['password']
        ]);

        return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.password_updated'));
    }
}
