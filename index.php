<?php
    require 'inc/helpers.php';
    require 'function.php';
    require 'cek.php';

    // Aktifkan tampilan error dan laporan kesalahan PHP (development only)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Fungsi untuk format harga
    function formatHargaIDR($harga) {
        return 'Rp ' . number_format($harga, 0, ',', '.');
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
        <meta property="og:title" content="Buku" />
        <meta property="og:description" content="Lihat detail pengiriman buku di sini." />
        <meta property="og:image" content="favicon_io/favicon.ico" />
        <meta property="og:url" content="https://stockbuku.com/detail_pengirim.php" />
        <meta name="twitter:card" content="summary_large_image" />
        <title>Stock Buku</title>
        <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
        <link rel="manifest" href="favicon_io/site.webmanifest">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="assets/css/theme.css" rel="stylesheet" />
        <link href="assets/css/loader.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            .zoomable { width: 50px; }
            .zoomable:hover { transform: scale(2.5); transition: 0.3s ease; }
            a { text-decoration: none; color: inherit; }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <div id="globalLoaderOverlay" class="loader-overlay" style="display:none"></div>
        <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Buku</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" role="search" aria-label="Search"></form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw" aria-hidden="true"></i><span class="visually-hidden">User menu</span></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="admin.php">Kelola Admin</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item logout-button" href="logout.php">Logout</a></li>
                    </ul>
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
                                <div class="sb-nav-link-icon"><i class="fas fa-warehouse" aria-hidden="true"></i></div>
                                Buku
                            </a>
                            <a class="nav-link" href="penerbit.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open" aria-hidden="true"></i></div>
                                Penerbit
                            </a>
                            <a class="nav-link" href="pengirim.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-truck" aria-hidden="true"></i></div>
                                Pengirim
                            </a>
                            <a class="nav-link" href="pembeli.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-friends" aria-hidden="true"></i></div>
                                Pembeli
                            </a>
                            <div class="sb-sidenav-menu-heading">Detail</div>
                            <a class="nav-link" href="detail_pembeli.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave" aria-hidden="true"></i></div>
                                Detail Pembeli
                            </a>
                            <a class="nav-link" href="detail_pengirim.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave" aria-hidden="true"></i></div>
                                Detail Pengirim
                            </a>
                            <div class="sb-sidenav-menu-heading">Laporan</div>
                            <a class="nav-link" href="pendapatan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave" aria-hidden="true"></i></div>
                                Hasil Pendapatan
                            </a>
                            <a class="nav-link" href="grafik.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area" aria-hidden="true"></i></div>
                                Grafik Laporan
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Stock Buku</h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="button-17" role="button" data-bs-toggle="modal" data-bs-target="#myModal">Import</button>
                                <a href="export.php" class="button-17" role="button" id="exportButton">Export Data</a>
                                <button class="button-17" onclick="toggleTheme()">Toggle Theme</button>
                            </div>
                            <div class="card-body">
                                <?php
                                    // Periksa buku dengan stok habis
                                    $queryStokHabis = $conn->prepare("SELECT judulbuku FROM buku WHERE stock < 1");
                                    $queryStokHabis->execute();
                                    $resultStokHabis = $queryStokHabis->get_result();

                                    while ($row = $resultStokHabis->fetch_assoc()) {
                                        $buku = htmlspecialchars($row['judulbuku']);
                                        echo "<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">";
                                        echo "<strong>Maaf!</strong> Stok $buku telah habis.";
                                        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                                        echo "</div>";
                                    }
                                    $queryStokHabis->close();
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Gambar</th>
                                                <th>Judul Buku</th>
                                                <th>Genre Buku</th>
                                                <th>Harga</th>
                                                <th>Stock</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // Ambil semua data buku
                                        $queryBuku = $conn->prepare("SELECT * FROM buku");
                                        $queryBuku->execute();
                                        $resultBuku = $queryBuku->get_result();

                                        $i = 1;
                                        while ($data = $resultBuku->fetch_assoc()) {
                                            $judulbuku = htmlspecialchars($data['judulbuku']);
                                            $harga = floatval($data['harga']);
                                            $stock = intval($data['stock']);
                                            $genre_buku = htmlspecialchars($data['genre_buku']);
                                            $idb = intval($data['idbuku']);
                                            $gambar = htmlspecialchars($data['image']);

                                            // Validasi dan tampilkan gambar
                                            $imagePath = 'images/' . $gambar;
                                            if ($gambar == null || !file_exists($imagePath)) {
                                                $img = '<img src="images/default.png" class="zoomable" alt="No Photo">';
                                            } else {
                                                $img = '<img src="' . $imagePath . '" class="zoomable" alt="Gambar Buku">';
                                            }
                                            ?>

                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $img; ?></td>
                                                <td><?= $judulbuku; ?></td>
                                                <td><?= $genre_buku; ?></td>
                                                <td><?= formatHargaIDR($harga); ?></td>
                                                <td><?= $stock; ?></td>
                                                <td>
                                                    <button type="button" class="button-17" role="button" data-bs-toggle="modal" data-bs-target="#edit<?= $idb; ?>">Edit</button>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Buku -->
                                            <div class="modal fade" id="edit<?= $idb; ?>" tabindex="-1" aria-labelledby="editLabel<?= $idb; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editLabel<?= $idb; ?>">Edit Buku</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data" action="api/update_buku.php" class="needs-validation" novalidate>
                                                            <div class="modal-body">
                                                                <input type="text" name="judulbuku" value="<?= $judulbuku; ?>" class="form-control" required aria-label="Judul Buku">
                                                                <br>
                                                                <input type="text" name="genre_buku" value="<?= $genre_buku; ?>" class="form-control" required aria-label="Genre Buku">
                                                                <br>
                                                                <input type="number" name="harga" value="<?= $harga; ?>" class="form-control" required aria-label="Harga">
                                                                <br>
                                                                <input type="number" name="stock" value="<?= $stock; ?>" class="form-control" min="0" required aria-label="Stock">
                                                                <br>
                                                                <input type="file" name="file" class="form-control" aria-label="Gambar Buku">
                                                                <br>
                                                                <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                                <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
                                                                <button type="submit" class="button-17" role="button" name="updatebuku">Edit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
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
                            <div class="text-muted" id="copyright"></div>
                            <div>
                                <a href="privacy.php" class="text-dark">Privacy Policy</a>
                                &middot;
                                <a href="#" class="text-dark">Terms &amp; Conditions</a>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="assets/js/theme.js"></script>
        <script src="assets/js/loader.js"></script>
        <script src="assets/js/validation.js"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const logoutButton = document.querySelector('.logout-button');
                const loader = document.getElementById('globalLoaderOverlay');
                const exportButton = document.getElementById('exportButton');

                if (logoutButton) {
                    logoutButton.addEventListener('click', function(e) {
                        // Show loader to indicate logout in progress
                        showLoader();
                    });
                }

                if (exportButton) {
                    exportButton.addEventListener('click', function() {
                        showLoader();
                    });
                }

                // Bootstrap 5: enable client-side form validation for modals
                (function () {
                  'use strict'
                  var forms = document.querySelectorAll('.needs-validation')
                  Array.prototype.slice.call(forms).forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                      if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                        Swal.fire({icon:'error', title:'Form belum lengkap', text:'Isi semua field yang diperlukan.'});
                      }
                      form.classList.add('was-validated')
                    }, false)
                  })
                })()
            });
        </script>
    </body>
        <!-- Modal for Import -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Import data buku</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <form method="post" enctype="multipart/form-data" action="api/import_buku.php" class="needs-validation" novalidate>
                        <div class="modal-body">
                            <p>Silakan unggah file Excel untuk import data buku.</p>
                            <input type="file" name="excel_file" accept=".xlsx, .xls" class="form-control" required aria-label="File Excel untuk Import">
                            <input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
                            <br>
                            <button type="submit" class="button-17" role="button" name="submit_import">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</html>
