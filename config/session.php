<?php

// Session driver.
// session: regular $_SESSION
// cookie: encrypted cookie-based sessions
define('SESSION_DRIVER', getenv('SESSION_DRIVER'));

// Name of session cookie.
define('SESSION_COOKIE_NAME', getenv('SESSION_COOKIE_NAME'));

// Duration for session cookies.
define('SESSION_COOKIE_DURATION', 86400);

// Domain name for session cookies.
define('SESSION_COOKIE_DOMAIN', getenv('SESSION_COOKIE_DOMAIN'));

// Whether session cookies should be secure (HTTPS only).
define('SESSION_COOKIE_SECURE', getenv('MODE') == 'production');

// Whether session cookies should be HTTP only.
define('SESSION_COOKIE_HTTP_ONLY', true);
