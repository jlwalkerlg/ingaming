<?php

// Project root.
define('PROJECT_ROOT', dirname(__DIR__));

// App root.
define('APP_ROOT', PROJECT_ROOT . '/app');

// Public root.
define('PUBLIC_ROOT', PROJECT_ROOT . '/public');

// URL root
define('URL_ROOT', '/');

// Site name.
define('SITE_NAME', 'inGAMING');

// Tiny MCE API Key.
define('TINYMCE_KEY', getenv('TINYMCE_KEY'));

// Cart config.
define('CART_COOKIE_NAME', 'ingaming_cart');
define('CART_COOKIE_DURATION', 60 * 60 * 24 * 14);
