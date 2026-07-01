<?php
// api/import_penerbit.php (skeleton)
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';

secure_session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); exit;
}

if (empty($_SESSION['user_id'])) {
    http_response_code(401); exit;
}

$csrf = $_POST['_csrf'] ?? '';
if (!validate_csrf($csrf)) { http_response_code(400); exit; }

if (empty($_FILES['file_excel'])) { http_response_code(400); echo json_encode(['error'=>'No file']); exit; }

try {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['file_excel']['tmp_name']);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();
    $pdo->beginTransaction();
    $insert = $pdo->prepare('INSERT INTO penerbit (idpenerbit, nama, email, negara_asal) VALUES (:id, :nama, :email, :negara)');
    foreach ($rows as $i => $r) {
        if ($i === 0) continue;
        $id = trim($r[0] ?? '');
        $nama = trim($r[1] ?? '');
        $email = trim($r[2] ?? '');
        $negara = trim($r[3] ?? '');
        if (!$id || !$nama || !$email) continue;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;
        // check exist
        $c = $pdo->prepare('SELECT idpenerbit FROM penerbit WHERE idpenerbit = :id');
        $c->execute([':id'=>$id]);
        if ($c->fetch()) continue;
        $insert->execute([':id'=>$id, ':nama'=>$nama, ':email'=>$email, ':negara'=>$negara]);
    }
    $pdo->commit();
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()]);
}
