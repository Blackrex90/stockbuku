<?php
    require 'function.php';
    require 'cek.php';

    // Aktifkan tampilan error dan laporan kesalahan PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Mengambil buku paling banyak dibeli
    $palingBanyakDibeliQuery = mysqli_query($conn, "
    SELECT b.judulbuku, SUM(dp.qty) AS total_beli 
    FROM detil_pembeli dp
    JOIN buku b ON dp.idbuku = b.idbuku 
    GROUP BY dp.idbuku 
    ORDER BY total_beli DESC 
    LIMIT 5
    ");
    $palingBanyakDibeliData = [];
    while ($row = mysqli_fetch_assoc($palingBanyakDibeliQuery)) {
        $palingBanyakDibeliData[] = $row;
    }

    // Mengambil buku dengan jumlah sedikit
    $jumlahSedikitQuery = mysqli_query($conn, "
    SELECT judulbuku, stock 
    FROM buku 
    ORDER BY stock ASC 
    LIMIT 5
    ");
    $jumlahSedikitData = [];
    while ($row = mysqli_fetch_assoc($jumlahSedikitQuery)) {
        $jumlahSedikitData[] = $row;
    }

    // Mengambil pembeli yang sering beli buku
    $pembeliSeringBeliQuery = mysqli_query($conn, "
    SELECT p.nama_pembeli, COUNT(dp.idpembeli) AS total_beli 
    FROM detil_pembeli dp
    JOIN pembeli p ON dp.idpembeli = p.idpembeli 
    GROUP BY dp.idpembeli 
    ORDER BY total_beli DESC 
    LIMIT 5
    ");
    $pembeliSeringBeliData = [];
    while ($row = mysqli_fetch_assoc($pembeliSeringBeliQuery)) {
        $pembeliSeringBeliData[] = $row;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Grafik" />
        <meta name="author" content="Blackrex" />
        <meta property="og:title" content="Grafik" />
        <meta property="og:image" content="favicon_io/favicon.ico" />
        <meta property="og:url" content="https://stockbuku.com/grafik.php" />
        <meta name="twitter:card" content="summary_large_image" />
        <title>Grafik</title>
        <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
        <link rel="manifest" href="favicon_io/site.webmanifest">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <nav class="sb-topnav navbar navbar-expand navbar-ligth bg-ligth">
            <a class="navbar-brand" href="grafik.php">Grafik Laporan</a>
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
                        <h1>Grafik Laporan Stock Buku</h1>
                        <div class="row grafik d-flex justify-content-around text-center">
                            <!-- Grafik Buku Paling Banyak Dibeli -->
                            <div id="grafik-1" class="col-lg-7 col-md-12 my-4">
                                <h2>Buku Paling Banyak Dibeli</h2>
                                <canvas id="bukuPalingBanyakDibeliChart"></canvas>
                            </div>

                            <!-- Grafik Buku dengan Jumlah Sedikit -->
                            <div id="grafik-2" class="col-lg-7 col-md-12 my-4">
                                <h2>Buku dengan Jumlah Sedikit</h2>
                                <canvas id="bukuJumlahSedikitChart"></canvas>
                            </div>

                            <!-- Grafik Pembeli yang Sering Beli Buku -->
                            <div id="grafik-3" class="col-lg-7 col-md-12 my-4">
                                <h2>Pembeli yang Sering Beli Buku</h2>
                                <canvas id="pembeliSeringBeliChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Data untuk Buku Paling Banyak Dibeli
                        const bukuPalingBanyakDibeliLabels = <?= json_encode(array_column($palingBanyakDibeliData, 'judulbuku'), JSON_HEX_TAG); ?>;
                        const bukuPalingBanyakDibeliTotals = <?= json_encode(array_column($palingBanyakDibeliData, 'total_beli'), JSON_HEX_TAG); ?>;

                        // Data untuk Buku dengan Stok Terkecil
                        const bukuJumlahSedikitLabels = <?= json_encode(array_column($jumlahSedikitData, 'judulbuku'), JSON_HEX_TAG); ?>;
                        const bukuJumlahSedikitStocks = <?= json_encode(array_column($jumlahSedikitData, 'stock'), JSON_HEX_TAG); ?>;

                        // Data untuk Pembeli Paling Aktif
                        const pembeliSeringBeliLabels = <?= json_encode(array_column($pembeliSeringBeliData, 'nama_pembeli'), JSON_HEX_TAG); ?>;
                        const pembeliSeringBeliTotals = <?= json_encode(array_column($pembeliSeringBeliData, 'total_beli'), JSON_HEX_TAG); ?>;

                        // Grafik Buku Paling Banyak Dibeli
                        new Chart(document.getElementById('bukuPalingBanyakDibeliChart'), {
                            type: 'bar',
                            data: {
                                labels: bukuPalingBanyakDibeliLabels,
                                datasets: [{
                                    label: 'Jumlah Pembelian',
                                    data: bukuPalingBanyakDibeliTotals,
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            }
                        });

                        // Grafik Buku dengan Jumlah Sedikit
                        new Chart(document.getElementById('bukuJumlahSedikitChart'), {
                            type: 'bar',
                            data: {
                                labels: bukuJumlahSedikitLabels,
                                datasets: [{
                                    label: 'Stok Buku',
                                    data: bukuJumlahSedikitStocks,
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }]
                            }
                        });

                        // Grafik Pembeli Paling Aktif
                        new Chart(document.getElementById('pembeliSeringBeliChart'), {
                            type: 'bar',
                            data: {
                                labels: pembeliSeringBeliLabels,
                                datasets: [{
                                    label: 'Jumlah Transaksi',
                                    data: pembeliSeringBeliTotals,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            }
                        });
                    </script>
                </main>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const logoutButton = document.querySelector('.logout-button');
                const loader = document.querySelector('.loader');

                if (logoutButton) {
                    logoutButton.addEventListener('click', function() {
                        loader.style.display = 'flex';
                    });
                }
            });
        </script>
    </body>
</html>