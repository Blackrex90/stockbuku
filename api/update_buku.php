<?php
// api/update_buku.php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/upload_helper.php';

secure_session_start();
header('Content-Type: application/json; charset=utf-8');

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

$idb = isset($_POST['idb']) ? (int)$_POST['idb'] : 0;
$judul = validate_input($_POST['judulbuku'] ?? '');
$genre = validate_input($_POST['genre_buku'] ?? '');
$harga = isset($_POST['harga']) ? filter_var($_POST['harga'], FILTER_VALIDATE_FLOAT) : null;
$stock = isset($_POST['stock']) ? filter_var($_POST['stock'], FILTER_VALIDATE_INT) : null;

if (!$idb || !$judul || !$genre || $harga === false || $stock === false) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

try {
    // If file uploaded, validate & store
    $imageName = null;
    if (!empty($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $imagesDir = getenv('IMAGES_DIR') ?: __DIR__ . '/../images/uploads';
        // if IMAGES_DIR is relative, make absolute
        if (!str_starts_with($imagesDir, '/') && !preg_match('/^[A-Za-z]:\\\\/', $imagesDir)) {
            $imagesDir = __DIR__ . '/../' . ltrim($imagesDir, '/');
        }
        $imageName = validate_and_store_image($_FILES['file'], $imagesDir);
    }

    // Build update SQL
    $fields = ['judulbuku' => $judul, 'genre_buku' => $genre, 'harga' => $harga, 'stock' => $stock];
    if ($imageName) $fields['image'] = $imageName;

    $setParts = [];
    $params = [':id' => $idb];
    foreach ($fields as $k => $v) {
        $setParts[] = "$k = :$k";
        $params[":$k"] = $v;
    }
    $sql = 'UPDATE buku SET ' . implode(', ', $setParts) . ' WHERE idbuku = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Update failed', 'detail' => $e->getMessage()]);
}
