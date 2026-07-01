<?php
// api/import_buku.php
// Import buku from uploaded Excel file (skeleton)
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/upload_helper.php';

secure_session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$csrf = $_POST['_csrf'] ?? '';
if (!validate_csrf($csrf)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

if (empty($_FILES['excel_file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

// Save uploaded file temporarily
$tmpDir = sys_get_temp_dir();
$tmpFile = $_FILES['excel_file']['tmp_name'];

// Basic MIME check (allow Excel types)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $tmpFile);
finfo_close($finfo);
$allowed = [
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-excel',
    'application/octet-stream'
];
if (!in_array($mime, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type']);
    exit;
}

// Use PhpSpreadsheet to parse file (composer dependency required)
try {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpFile);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $pdo->beginTransaction();
    $insertStmt = $pdo->prepare('INSERT INTO buku (judulbuku, genre_buku, harga, stock, image) VALUES (:judul, :genre, :harga, :stock, :image)');

    foreach ($rows as $index => $row) {
        if ($index === 0) continue; // skip header
        $judul = trim($row[0] ?? '');
        $genre = trim($row[1] ?? '');
        $harga = is_numeric($row[2]) ? (float)$row[2] : null;
        $stock = is_numeric($row[3]) ? (int)$row[3] : null;
        $image = trim($row[4] ?? '');

        if (!$judul || !$genre || $harga === null || $stock === null) {
            // skip invalid row
            continue;
        }

        // check duplicate
        $check = $pdo->prepare('SELECT COUNT(*) FROM buku WHERE judulbuku = :judul AND genre_buku = :genre');
        $check->execute([':judul' => $judul, ':genre' => $genre]);
        if ($check->fetchColumn() > 0) continue;

        // if image referenced, leave as-is (assumes image already uploaded to images/)
        $imageName = $image ?: null;

        $insertStmt->execute([
            ':judul' => $judul,
            ':genre' => $genre,
            ':harga' => $harga,
            ':stock' => $stock,
            ':image' => $imageName
        ]);
    }
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Import failed', 'detail' => $e->getMessage()]);
}
