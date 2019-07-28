<?php

namespace App\Providers;

use Bellona\Support\ServiceProvider;

use App\Lib\Asset\Asset;

class AssetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Asset::class, function ($app) {
            return new Asset;
        });
    }
}
