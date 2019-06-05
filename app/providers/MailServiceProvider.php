<?php

namespace App\Providers;

use Bellona\Support\ServiceProvider;
use App\Lib\Mail\MailFactory;

class MailServiceProvider extends ServiceProvider
{
    public $defer = true;

    public $services = [MailFactory::class];

    public function register()
    {
        $this->app->singleton(MailFactory::class, function ($app) {
            return new MailFactory;
        });
    }
}
