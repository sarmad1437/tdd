<?php

namespace App\Services;

use App\DTO\Auth\LoginData;
use App\DTO\Auth\RegisterData;
use App\Exceptions\LoginException;
use App\Models\User;
use Auth;

class AuthService
{
    public function register(array $registerData): User
    {
        $registerData['password'] = bcrypt($registerData['password']);

        return User::create($registerData);
    }

    /**
     * @throws LoginException
     */
    public function login(array $loginData): array
    {
        if (! Auth::attempt($loginData)) {
            throw LoginException::incorrectCredentials();
        }

        $user = User::where('email', $loginData['email'])->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [$token, $user];
    }
}