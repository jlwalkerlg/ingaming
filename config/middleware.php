<?php

return [
    'csrf' => App\Middleware\CSRF::class,
    'auth' => App\Middleware\Authenticate::class,
    'can' => App\Middleware\Authorize::class
];
