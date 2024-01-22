<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(String $name)
    {
    }

    public function build(): self
    {
        return $this->view('emails.register');
    }
}
