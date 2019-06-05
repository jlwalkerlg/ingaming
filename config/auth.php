<?php

// Authentication driver.
// session: session-based authentication
// api: encrypted cookie-based authentication
define('AUTH_DRIVER', getenv('AUTH_DRIVER'));

// Name of auth cookie.
define('AUTH_COOKIE_NAME', getenv('AUTH_COOKIE_NAME'));

// Domain name for authentication cookies.
define('AUTH_COOKIE_DURATION', 60 * 60 * 24 * 14); // one week

// Domain name for authentication cookies.
define('AUTH_COOKIE_DOMAIN', getenv('AUTH_COOKIE_DOMAIN'));

// Whether session cookies should be secure (HTTPS only).
define('AUTH_COOKIE_SECURE', getenv('MODE') == 'production');

// Whether session cookies should be HTTP only.
define('AUTH_COOKIE_HTTP_ONLY', true);
