<?php

namespace App\Http\Controllers;

use App\DTO\Auth\RegisterData;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class RegisterController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(RegisterRequest $registerData)
    {
        $user = $this->authService->register($registerData->validated());

        $user = UserResource::make($user);

        return success($user, 'User register successfully');
    }
}
