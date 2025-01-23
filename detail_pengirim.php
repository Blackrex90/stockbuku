<?php
    require 'function.php';
    require 'cek.php';

    // Aktifkan tampilan error dan laporan kesalahan PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    function formatHargaIDR($harga) {
        return "Rp " . number_format($harga, 0, ',', '.');
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
        <meta property="og:title" content="Detail Pengirim Buku" />
        <meta property="og:description" content="Lihat detail pengiriman buku di sini." />
        <meta property="og:image" content="favicon_io/favicon.ico" />
        <meta property="og:url" content="https://stockbuku.com/detail_pengirim.php" />
        <meta name="twitter:card" content="summary_large_image" />
        <title>Detail Pengirim</title>
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
    <body class="sb-nav-fixed">
        <div class="loader">
            <div class="loader-dot"></div>
            <div class="loader-dot"></div>
            <div class="loader-dot"></div>
        </div>
        <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Stock Buku</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0"></form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="admin.php">Kelola Admin</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item logout-button" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Pengelolaan</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-warehouse"></i></div>
                                    Buku
                            </a>
                            <a class="nav-link" href="penerbit.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                    Penerbit
                            </a>
                            <a class="nav-link" href="pengirim.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                    Pengirim
                            </a>
                            <a class="nav-link" href="pembeli.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                    Pembeli
                            </a>
                            <div class="sb-sidenav-menu-heading">Detail</div>
                            <a class="nav-link" href="detail_pembeli.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    Detail Pembeli
                            </a>
                            <a class="nav-link" href="detail_pengirim.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    Detail Pengirim
                            </a>
                            <div class="sb-sidenav-menu-heading">Laporan</div>
                            <a class="nav-link" href="pendapatan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    Hasil Pendapatan
                            </a>
                            <a class="nav-link" href="grafik.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                    Grafik Laporan
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Detail Pengirim</h1>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                  <!-- Button to Open the Modal -->
                                  <button type="button" class="button-17" role="button" data-toggle="modal" data-target="#myModal">
                                    Import Data 
                                  </button>
                                  <a href="exportdetail_pengirim.php" class="button-17" role="button" id="exportButton" >Export Data</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor Bukti</th>
                                                <th>Judul Buku</th>
                                                <th>Qty</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                                $ambilsemuadatapengirim = mysqli_query($conn,"SELECT detil_pengirim.nobukti, buku.judulbuku, detil_pengirim.qty, detil_pengirim.harga FROM detil_pengirim JOIN buku ON detil_pengirim.idbuku = buku.idbuku");
                                                $i = 1;
                                                while ($data = mysqli_fetch_array($ambilsemuadatapengirim)) {
                                                    $nobukti = $data['nobukti'];
                                                    $judulbuku = $data['judulbuku'];
                                                    $qty = $data['qty'];
                                                    $harga = $data['harga'];
                                            ?>

                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $nobukti; ?></td>
                                                <td><?= $judulbuku; ?></td>
                                                <td><?= $qty; ?></td>
                                                <td><?= formatHargaIDR($harga); ?></td>
                                            </tr>

                                            <?php  
                                                };
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted" id="copyright">
                                <!-- JavaScript will add the current year here automatically -->
                            </div>
                            <div>
                                <a href="privacy.php">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var currentYear = new Date().getFullYear();
                                var footerYear = document.getElementById('copyright');
                                footerYear.innerHTML = 'Copyright &copy; ' + currentYear;
                            });
                        </script>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const logoutButton = document.querySelector('.logout-button');
                const loader = document.querySelector('.loader');

                if (logoutButton) {
                    logoutButton.addEventListener('click', function() {
                        loader.style.display = 'flex';
                    });
                }

                if (exportButton) {
                    exportButton.addEventListener('click', function() {
                        loader.style.display = 'flex';
                    });
                }
            });
        </script>
    </body>

    <!-- Modal for Import -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Import Data Pengiriman</h4>
                    <button type="button" class="button-17" role="button" data-dismiss="modal">&times;</button>            
                </div>
                
                <!-- Modal Body -->
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Silakan unggah file Excel untuk import data pengiriman.</p>
                        <input type="file" name="excel_file" accept=".xlsx, .xls" class="form-control" required>
                        <br>
                        <button type="submit" class="button-17" role="button" name="submit_import">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</html>