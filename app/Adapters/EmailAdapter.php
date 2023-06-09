<?php

namespace App\Adapters;

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class EmailAdapter implements EmailService
{
    public function sendEmail($to, $subject, $content)
    {
        Mail::to($to)->send(new WelcomeEmail($to, $subject, $content));
    }
}
