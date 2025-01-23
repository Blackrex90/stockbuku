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

        // Ambil data buku dari database
        $query = $conn->prepare("SELECT * FROM buku");
        $query->execute();
        $result = $query->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        switch ($format) {
            case 'csv':
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="data_buku.csv"');
                $output = fopen('php://output', 'w');
                fputcsv($output, array('ID', 'Judul Buku', 'Genre Buku', 'Harga', 'Stock'));
                foreach ($data as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
                exit;

            case 'excel':
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="data_buku.xlsx"');
                echo "<table border='1'><tr><th>ID</th><th>Judul Buku</th><th>Genre Buku</th><th>Harga</th><th>Stock</th></tr>";
                foreach ($data as $row) {
                    echo "<tr><td>{$row['idbuku']}</td><td>{$row['judulbuku']}</td><td>{$row['genre_buku']}</td><td>{$row['harga']}</td><td>{$row['stock']}</td></tr>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Export Data Buku</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <h1 class="mt-4">Export Data Buku</h1>
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
                <a href="index.php" class="button-17" id="backButton" >Kembali ke Index</a>
            </div>
        </form>
    </div>
    <script src="js/scripts.js"></script>
    <script>
        document.getElementById('backButton').addEventListener('click', function(event) {
            event.preventDefault();
            document.querySelector('.loader').style.display = 'flex';
            window.location.href = 'index.php';
        });
    </script>
</body>
</html>