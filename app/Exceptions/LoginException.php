<?php

namespace App\Exceptions;

class LoginException extends SysException
{
    public static function incorrectCredentials(): static
    {
        return new static('The provided credentials do not match our records.', 400);
    }
}
