<?php
require 'function.php'; // sesuaikan path jika perlu
// require 'cek.php'; // uncomment jika endpoint harus diautentikasi

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID buku tidak ditemukan']);
    exit;
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($id === false) {
    http_response_code(400);
    echo json_encode(['error' => 'ID tidak valid']);
    exit;
}

// Gunakan tabel 'buku' (ganti jika nama tabel berbeda)
$query = 'SELECT harga FROM buku WHERE idbuku = ? LIMIT 1';
$stmt = $conn->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal mempersiapkan query']);
    exit;
}

$stmt->bind_param('i', $id);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal mengeksekusi query']);
    $stmt->close();
    exit;
}

$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(['harga' => (float)$row['harga']]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Harga tidak tersedia']);
}

$stmt->close();
