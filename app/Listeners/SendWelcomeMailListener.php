<?php

namespace App\Listeners;

use App\Events\UserRegisteredEvent;

class SendWelcomeMailListener
{
    public function __construct()
    {
    }

    public function handle(UserRegisteredEvent $event): void
    {

    }
}
