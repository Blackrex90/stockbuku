<?php
// inc/db.php
// PDO database connection using environment variables (fallbacks provided)
declare(strict_types=1);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$dbname = getenv('DB_NAME') ?: 'stockbuku';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // Do not leak DB credentials or details to users
    error_log('DB connection error: ' . $e->getMessage());
    http_response_code(500);
    exit('Internal Server Error');
}

// Provide a mysqli-compatible $conn for legacy code that still uses it
// This keeps backward compatibility while new code should use $pdo
$mysqli_conn = new mysqli($host, $user, $pass, $dbname);
if ($mysqli_conn->connect_error) {
    error_log('MySQLi connection error: ' . $mysqli_conn->connect_error);
} else {
    // expose $conn variable used across project
    $conn = $mysqli_conn;
}

