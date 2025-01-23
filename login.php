<?php
    require 'function.php';

    // Aktifkan tampilan error dan laporan kesalahan PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Inisialisasi pesan error
    $errors = [];

    // Pastikan sesi aktif
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Proses login
    if (isset($_POST['login'])) {
        // Ambil data dari form dan lakukan sanitasi
        $email = validate_input($_POST['email']);
        $password = validate_input($_POST['password']);

        // Lakukan proses autentikasi di tabel login
        $query = "SELECT * FROM login WHERE email=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verifikasi password menggunakan password_verify
            if (password_verify($password, $row['password'])) {
                // Set session
                $_SESSION['log'] = true;
                $_SESSION['email'] = $row['email'];
                $_SESSION['username'] = $row['username'];

                // Redirect ke halaman dashboard
                header('Location: index.php');
                exit();
            } else {
                // Jika password tidak cocok, tampilkan pesan error
                $errors[] = "Invalid email or password!";
            }
        } else {
            // Jika email tidak ditemukan, tampilkan pesan error
            $errors[] = "Invalid email or password!";
        }
    }

    // Fungsi untuk validasi dan sanitasi input
    function validate_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Jika sudah login, redirect ke halaman dashboard
    if (isset($_SESSION['log'])) {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Detail Pengirim Buku" />
        <meta name="author" content="Blackrex" />
        <meta property="og:title" content="Login" />
        <meta property="og:description" content="Lihat detail pengiriman buku di sini." />
        <meta property="og:image" content="favicon_io/favicon.ico" />
        <meta property="og:url" content="https://stockbuku.com/detail_pengirim.php" />
        <meta name="twitter:card" content="summary_large_image" />
        <title>Login</title>
        <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
        <link rel="manifest" href="favicon_io/site.webmanifest">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .card {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loader {
            display: none; /* Initially hidden */
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: fixed;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
        }

        .loader-dot {
            width: 15px;
            height: 15px;
            margin: 0 5px;
            background-color: #333;
            border-radius: 50%;
            animation: loader-animation 1s infinite;
        }

        .loader-dot:nth-child(1) {
            animation-delay: 0s;
        }

        .loader-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .loader-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes loader-animation {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="bg-primary">
    <div class="loader">
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
                                                <p><?php echo $error; ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form method="post" id="loginForm">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputUsername">Username</label>
                                            <input class="form-control py-4" name="username" id="inputUsername" type="text" placeholder="Enter Username" required/>
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">Email</label>
                                            <input class="form-control py-4" name="email" id="inputEmailAddress" type="email" placeholder="Enter email address" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <input class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Enter password" required/>
                                        </div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="button-17" role="button" name="login" type="submit">Login</button>
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
            const loader = document.querySelector('.loader');

            loginForm.addEventListener('submit', function() {
                loader.style.display = 'flex';
            });
        });
    </script>
</body>
</html>
