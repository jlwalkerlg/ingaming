<?php

namespace App\Providers;

use Bellona\Support\ServiceProvider;

class PurifierServiceProvider extends ServiceProvider
{
    public $defer = true;

    public $services = ['purifier'];

    public function register()
    {
        $this->app->singleton('purifier', function($app) {
            $config = \HTMLPurifier_Config::createDefault();
            return new \HTMLPurifier($config);
        });
    }
}
