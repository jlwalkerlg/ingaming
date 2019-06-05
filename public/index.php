<?php

// Register composer autoloader.
require_once '../vendor/autoload.php';

// Load environment variables.
$dotenv = Dotenv\Dotenv::create(dirname(__DIR__));
$dotenv->load();

// Require config file.
require_once '../config/index.php';

// List of service providers to register with app.
$servicesProviders = [
    // Core services.
    Bellona\Http\RequestServiceProvider::class,
    Bellona\Http\RoutingServiceProvider::class,
    Bellona\Database\DatabaseServiceProvider::class,
    Bellona\Session\SessionServiceProvider::class,
    Bellona\Security\CSRFServiceProvider::class,
    Bellona\Auth\AuthServiceProvider::class,
    Bellona\View\ViewServiceProvider::class,
    Bellona\Cookie\CookieServiceProvider::class,
    Bellona\Encryption\EncryptServiceProvider::class,

    // Lib services.
    App\Providers\MailServiceProvider::class,

    // Custom services.
    App\Providers\AppServiceProvider::class,
    App\Providers\AssetServiceProvider::class,
    App\Providers\PurifierServiceProvider::class
];

// Class aliases for use within views.
$aliases = [
    'Router' => Bellona\Support\Facades\Router::class,
    'Session' => Bellona\Support\Facades\Session::class,
    'CSRF' => Bellona\Support\Facades\CSRF::class,
    'Auth' => Bellona\Support\Facades\Auth::class,

    // Custom aliases
    'Asset' => App\Facades\Asset::class
];

// Register aliases.
foreach ($aliases as $alias => $original) {
    class_alias($original, $alias);
}

// Turn on output buffering.
ob_start();

// Initialise app and register services.
$app = new Bellona\Core\Application($servicesProviders);

// Boot app services.
$app->boot();

// Route incoming request.
$router = $app['Bellona\Http\Router'];
$router->route();
