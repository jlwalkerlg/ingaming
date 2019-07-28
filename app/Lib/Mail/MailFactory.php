<?php

namespace App\Lib\Mail;

use App\Lib\Mail\Gmail;

class MailFactory
{
    public function make(string $driver)
    {
        if ($driver === 'gmail') {
            return new Gmail;
        }
    }
}
