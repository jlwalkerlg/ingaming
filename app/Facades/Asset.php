<?php

namespace App\Facades;

use Bellona\Support\Facades\Facade;

use App\Lib\Asset\Asset as AssetService;

class Asset extends Facade
{
    public static $service = AssetService::class;
}
