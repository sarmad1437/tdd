<?php

namespace App\Http\Controllers;

use App\DTO\Auth\LoginData;
use App\Exceptions\LoginException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class LoginController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @throws LoginException
     */
    public function __invoke(LoginRequest $request)
    {
        [$token, $user] = $this->authService->login($request->validated());

        return success([
            'token' => $token,
            'user' => new UserResource($user),
        ], 'User login successfully');

    }
}
