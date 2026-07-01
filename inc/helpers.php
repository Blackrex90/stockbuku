<?php
// inc/helpers.php
// Central helper utilities: secure session, CSRF, sanitization, flash messages, format, fines

// Start a secure session (call at top of scripts)
function secure_session_start(): void {
    if (session_status() === PHP_SESSION_NONE) {
        // Secure session cookie params
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'] ?? '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }
    // Prevent session fixation
    if (empty($_SESSION['created'])) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

// CSRF token helpers
function csrf_token(): string {
    secure_session_start();
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function validate_csrf(string $token): bool {
    secure_session_start();
    return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
}

// Safe redirect
function safe_redirect(string $url): void {
    // sanitize url
    $url = filter_var($url, FILTER_SANITIZE_URL);
    header('Location: ' . $url);
    exit();
}

// Input sanitization helpers
function validate_input(?string $data): ?string {
    if ($data === null) return null;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// Flash messages using session
function flash_set(string $key, string $message): void {
    secure_session_start();
    $_SESSION['_flash'][$key] = $message;
}
function flash_get(string $key): ?string {
    secure_session_start();
    if (!isset($_SESSION['_flash'][$key])) return null;
    $msg = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);
    return $msg;
}

// Date/Time formatting: DD/MM/YYYY HH:MM
function formatDateTime(?string $ts): string {
    if (empty($ts)) return '';
    try {
        $dt = new DateTime($ts);
    } catch (Exception $e) {
        return '';
    }
    return $dt->format('d/m/Y H:i');
}

// Fine and extension helpers
function calculate_fine(string $dueDateTime, ?string $returnedDateTime = null): int {
    try {
        $due = new DateTime($dueDateTime);
    } catch (Exception $e) {
        return 0;
    }
    $now = $returnedDateTime ? new DateTime($returnedDateTime) : new DateTime();
    if ($now <= $due) return 0;
    $diff = $due->diff($now);
    $daysLate = (int)$diff->format('%a');
    return $daysLate * 5000; // Rp 5,000 per day
}

function can_extend(string $dueDateTime, ?string $nowDateTime = null): bool {
    try {
        $due = new DateTime($dueDateTime);
    } catch (Exception $e) {
        return false;
    }
    $now = $nowDateTime ? new DateTime($nowDateTime) : new DateTime();
    if ($now > $due) return false; // already past due
    $daysLeft = (int)$now->diff($due)->format('%a');
    // allow extension only if within 3 days before due
    return ($daysLeft <= 3 && $daysLeft >= 0);
}

// SweetAlert helpers (echo JS snippet for messages)
function showSuccessMessage(string $message, string $redirectURL = ''): void {
    $redirect = $redirectURL ? addslashes($redirectURL) : '';
    $msg = addslashes($message);
    echo "<script>
    document.addEventListener('DOMContentLoaded', function(){
        if (window.Swal) {
            Swal.fire({icon:'success', title:'Berhasil!', text:'$msg', showConfirmButton:false, timer:1500}).then(function(){ if('$redirect'!=='') window.location.href='$redirect'; });
        } else {
            if('$redirect'!=='') window.location.href='$redirect';
            else alert('$msg');
        }
    });
    </script>";
}

function showErrorMessage(string $message, string $redirectURL = ''): void {
    $redirect = $redirectURL ? addslashes($redirectURL) : '';
    $msg = addslashes($message);
    echo "<script>
    document.addEventListener('DOMContentLoaded', function(){
        if (window.Swal) {
            Swal.fire({icon:'error', title:'Error', text:'$msg', showConfirmButton:false, timer:2500}).then(function(){ if('$redirect'!=='') window.location.href='$redirect'; });
        } else {
            if('$redirect'!=='') window.location.href='$redirect';
            else alert('$msg');
        }
    });
    </script>";
}

