<?php

namespace App\Adapters;

interface EmailService
{
    public function sendEmail($to, $subject, $content);
}
