<?php
    require 'function.php';
    require 'cek.php';

    // Aktifkan tampilan error dan laporan kesalahan PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Fungsi untuk meng-export data ke format yang diinginkan
    function exportData($format) {
        global $conn;

        // Ambil data detail pengirim dari database
        $query = $conn->prepare("SELECT detil_pengirim.nobukti, buku.judulbuku, detil_pengirim.qty, detil_pengirim.harga FROM detil_pengirim 
                                 JOIN buku ON detil_pengirim.idbuku = buku.idbuku");
        $query->execute();
        $result = $query->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        switch ($format) {
            case 'csv':
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="data_detail_pengirim.csv"');
                $output = fopen('php://output', 'w');
                fputcsv($output, array('Nomor Bukti', 'Judul Buku', 'Qty', 'Harga'));
                foreach ($data as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
                exit;

            case 'excel':
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="data_detail_pengirim.xlsx"');
                echo "<table border='1'><tr><th>Nomor Bukti</th><th>Judul Buku</th><th>Qty</th><th>Harga</th></tr>";
                foreach ($data as $row) {
                    echo "<tr><td>{$row['nobukti']}</td><td>{$row['judulbuku']}</td><td>{$row['qty']}</td><td>{$row['harga']}</td></tr>";
                }
                echo "</table>";
                exit;

            default:
                echo "Format tidak dikenali.";
                exit;
        }
    }

    // Proses permintaan export
    if (isset($_POST['export'])) {
        $format = $_POST['format'];
        exportData($format);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Export Detail Pengirim" />
    <meta name="author" content="Blackrex" />
    <meta property="og:title" content="Export Detail Pengirim" />
    <meta property="og:image" content="favicon_io/favicon.ico" />
    <meta property="og:url" content="https://stockbuku.com/exportdetail_pengirim.php" />
    <meta name="twitter:card" content="summary_large_image" />
    <title>Export Detail Pengirim</title>
    <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
    <link rel="manifest" href="favicon_io/site.webmanifest">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
<body>
    <div class="loader">
        <div class="loader-dot"></div>
        <div class="loader-dot"></div>
        <div class="loader-dot"></div>
    </div>
    <div class="container">
        <h1 class="mt-4">Export Data Detail Pengirim</h1>
        <form method="post">
            <div class="form-group">
                <label for="format">Pilih Format:</label>
                <select name="format" id="format" class="form-control" required>
                    <option value="csv">CSV</option>
                    <option value="excel">Excel</option>
                </select>
            </div>
            <div class="button-container">
                <button type="submit" name="export" class="button-17">Export</button>
                <a href="detail_pengirim.php" class="button-17" id="backButton">Kembali ke Detail Pengirim</a>
            </div>
        </form>
    </div>
    <script src="js/scripts.js"></script>
    <script>
        document.getElementById('backButton').addEventListener('click', function(event) {
            event.preventDefault();
            document.querySelector('.loader').style.display = 'flex';
            window.location.href = 'detail_pengirim.php';
        });
    </script>
</body>
</html>