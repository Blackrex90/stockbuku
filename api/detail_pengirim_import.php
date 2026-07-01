<?php
// api/detail_pengirim_import.php (skeleton)
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
secure_session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (empty($_SESSION['user_id'])) { http_response_code(401); exit; }
$csrf = $_POST['_csrf'] ?? '';
if (!validate_csrf($csrf)) { http_response_code(400); exit; }

if (empty($_FILES['excel_file'])) { http_response_code(400); echo json_encode(['error'=>'No file']); exit; }

try {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel_file']['tmp_name']);
    $rows = $spreadsheet->getActiveSheet()->toArray();
    $pdo->beginTransaction();
    $insertDet = $pdo->prepare('INSERT INTO detil_pengirim (nobukti, idbuku, harga, qty) VALUES (:n, :idb, :harga, :qty)');
    foreach ($rows as $i => $r) {
        if ($i === 0) continue;
        $nobukti = trim($r[0] ?? '');
        $judul = trim($r[1] ?? '');
        $qty = is_numeric($r[2]) ? (int)$r[2] : 0;
        $hargaRaw = is_numeric($r[3]) ? (float)$r[3] : 0.0;
        if (!$nobukti || !$judul || $qty <= 0 || $hargaRaw <= 0) continue;
        // ensure nobukti exists in pengirim
        $cp = $pdo->prepare('SELECT nobukti FROM pengirim WHERE nobukti = :n'); $cp->execute([':n'=>$nobukti]); if (!$cp->fetch()) continue;
        // find or create buku
        $qb = $pdo->prepare('SELECT idbuku, harga FROM buku WHERE judulbuku = :j LIMIT 1'); $qb->execute([':j'=>$judul]); $rb = $qb->fetch();
        if (!$rb) {
            $insB = $pdo->prepare('INSERT INTO buku (judulbuku, stock, harga) VALUES (:j, :stock, :harga)');
            $insB->execute([':j'=>$judul, ':stock'=>$qty, ':harga'=>$hargaRaw]);
            $idb = $pdo->lastInsertId();
        } else {
            $idb = $rb['idbuku'];
            $upd = $pdo->prepare('UPDATE buku SET stock = stock + :q WHERE idbuku = :id'); $upd->execute([':q'=>$qty, ':id'=>$idb]);
        }
        // compute import price (example: add 10% tax)
        $harga = $hargaRaw * 1.10;
        $insertDet->execute([':n'=>$nobukti, ':idb'=>$idb, ':harga'=>$harga, ':qty'=>$qty]);
    }
    $pdo->commit();
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()]);
}
