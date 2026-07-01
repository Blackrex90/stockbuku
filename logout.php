<?php
// logout.php (refactored)
require 'inc/helpers.php';

// Start secure session
secure_session_start();

// Clear session data
$_SESSION = [];

// Destroy session cookie if used
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'] ?? '/',
        $params['domain'] ?? '',
        $params['secure'] ?? false,
        $params['httponly'] ?? true
    );
}

// Destroy the session server side
session_destroy();

// Redirect to login with a query flag
safe_redirect('login.php?logged_out=1');
