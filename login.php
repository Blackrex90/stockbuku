<?php
require 'inc/db.php';
require 'inc/helpers.php';

// Start secure session
secure_session_start();

// Default error reporting for dev (can be disabled in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errors = [];

// If already logged in, redirect
if (!empty($_SESSION['user_id'])) {
    safe_redirect('index.php');
}

// Handle POST login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted_csrf = $_POST['_csrf'] ?? '';
    if (!validate_csrf($posted_csrf)) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $loginInput = validate_input($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($loginInput) || empty($password)) {
            $errors[] = 'Silakan isi semua field.';
        } else {
            // Prepared statement using PDO
            $stmt = $pdo->prepare('SELECT iduser, username, email, password, role FROM login WHERE email = :input OR username = :input LIMIT 1');
            $stmt->execute([':input' => $loginInput]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Successful login
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['iduser'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'] ?? 'user';
                $_SESSION['LAST_ACTIVITY'] = time();

                // Redirect to dashboard
                safe_redirect('index.php');
            } else {
                // Generic message to avoid user enumeration
                $errors[] = 'Email/Username atau password tidak valid.';
            }
        }
    }
}

$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login</title>
    <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
    <link rel="manifest" href="favicon_io/site.webmanifest">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .card { opacity: 0; animation: fadeIn 0.5s ease forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
        .loader { display: none; justify-content: center; align-items: center; height: 100vh; position: fixed; width: 100%; background-color: rgba(255,255,255,0.8); z-index: 9999; }
        .loader-dot { width: 15px; height: 15px; margin: 0 5px; background-color: #333; border-radius: 50%; animation: loader-animation 1s infinite; }
        @keyframes loader-animation { 0%,80%,100%{ transform: scale(0); } 40% { transform: scale(1); } }
    </style>
</head>
<body class="bg-primary">
    <div class="loader" id="globalLoader">
        <div class="loader-dot"></div>
        <div class="loader-dot"></div>
        <div class="loader-dot"></div>
    </div>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                <div class="card-body">
                                    <?php if (!empty($errors)) : ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php foreach ($errors as $error) : ?>
                                                <p><?php echo e($error); ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" id="loginForm" novalidate>
                                        <input type="hidden" name="_csrf" value="<?php echo $csrf; ?>">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputLogin">Email or Username</label>
                                            <input class="form-control py-4" name="login" id="inputLogin" type="text" placeholder="Enter email or username" required autofocus />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <input class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Enter password" required/>
                                        </div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="button-17" role="button" name="loginBtn" type="submit">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loader = document.getElementById('globalLoader');

            loginForm.addEventListener('submit', function() {
                loader.style.display = 'flex';
            });
        });
    </script>
</body>
</html>
