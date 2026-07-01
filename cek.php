<?php
require 'inc/helpers.php';

// Start secure session and check authentication
secure_session_start();

// Session timeout (3600 seconds = 1 hour)
$session_duration = 3600;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_duration)) {
    // Session expired
    session_unset();
    session_destroy();
    safe_redirect('login.php?timeout=1');
}

$_SESSION['LAST_ACTIVITY'] = time();

// Verify user is logged in
if (empty($_SESSION['user_id'])) {
    safe_redirect('login.php');
}

// Optional: role-based access helper can be used in pages
function require_role(string $role): void {
    if (empty($_SESSION['role']) || $_SESSION['role'] !== $role) {
        safe_redirect('index.php');
    }
}

?>
