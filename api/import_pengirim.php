<?php
// api/import_pengirim.php (skeleton)
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';

secure_session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (empty($_SESSION['user_id'])) { http_response_code(401); exit; }
$csrf = $_POST['_csrf'] ?? '';
if (!validate_csrf($csrf)) { http_response_code(400); exit; }

if (empty($_FILES['file_excel'])) { http_response_code(400); echo json_encode(['error'=>'No file']); exit; }

try {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['file_excel']['tmp_name']);
    $rows = $spreadsheet->getActiveSheet()->toArray();
    $pdo->beginTransaction();
    $insert = $pdo->prepare('INSERT INTO pengirim (idpenerbit, tanggal_kirim, nobukti, nama) VALUES (:idpenerbit, :tanggal, :nobukti, :nama)');
    foreach ($rows as $i => $r) {
        if ($i === 0) continue;
        $idp = trim($r[0] ?? '');
        $tanggal = trim($r[1] ?? '');
        $nobukti = trim($r[2] ?? '');
        if (!$idp || !$tanggal || !$nobukti) continue;
        // validate date
        $d = date('Y-m-d', strtotime($tanggal));
        $check = $pdo->prepare('SELECT idpenerbit FROM penerbit WHERE idpenerbit = :id'); $check->execute([':id'=>$idp]);
        if (!$check->fetch()) continue;
        $c2 = $pdo->prepare('SELECT nobukti FROM pengirim WHERE nobukti = :n'); $c2->execute([':n'=>$nobukti]);
        if ($c2->fetch()) continue;
        $insert->execute([':idpenerbit'=>$idp, ':tanggal'=>$d, ':nobukti'=>$nobukti, ':nama'=>null]);
    }
    $pdo->commit();
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()]);
}
