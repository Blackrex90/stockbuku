<?php
// export.php - improved CSV streaming with auth check
require_once 'inc/db.php';
require_once 'inc/helpers.php';
secure_session_start();

if (empty($_SESSION['user_id'])) {
    safe_redirect('login.php');
}

// stream CSV of buku
$filename = 'export_buku_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$output = fopen('php://output', 'w');
if ($output === false) exit;
// write BOM for Excel compatibility
fwrite($output, "\xEF\xBB\xBF");
// header
fputcsv($output, ['ID', 'Judul', 'Genre', 'Harga', 'Stock', 'Image']);

// fetch via cursor to avoid memory spike
$stmt = $pdo->prepare('SELECT idbuku, judulbuku, genre_buku, harga, stock, image FROM buku');
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [$row['idbuku'], $row['judulbuku'], $row['genre_buku'], $row['harga'], $row['stock'], $row['image']]);
}
fclose($output);
exit;
