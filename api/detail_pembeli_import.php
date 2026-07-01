<?php
// api/detail_pembeli_import.php (skeleton)
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
    $insert = $pdo->prepare('INSERT INTO detil_pembeli (idpembeli, idbuku, qty, harga) VALUES (:idpembeli, :idbuku, :qty, :harga)');
    foreach ($rows as $i => $r) {
        if ($i === 0) continue;
        $nama = trim($r[0] ?? '');
        $judul = trim($r[1] ?? '');
        $qty = is_numeric($r[2]) ? (int)$r[2] : 0;
        if (!$nama || !$judul || $qty <= 0) continue;
        // lookup pembeli
        $q = $pdo->prepare('SELECT idpembeli FROM pembeli WHERE nama_pembeli = :n LIMIT 1'); $q->execute([':n'=>$nama]);
        $rowp = $q->fetch(); if (!$rowp) continue; $idp = $rowp['idpembeli'];
        // lookup buku
        $qb = $pdo->prepare('SELECT idbuku, harga, stock FROM buku WHERE judulbuku = :j LIMIT 1'); $qb->execute([':j'=>$judul]);
        $rb = $qb->fetch(); if (!$rb) continue; $idb = $rb['idbuku']; $harga = $rb['harga']; $stock = $rb['stock'];
        if ($stock < $qty) continue; // skip if insufficient stock
        // update stock
        $u = $pdo->prepare('UPDATE buku SET stock = stock - :q WHERE idbuku = :id'); $u->execute([':q'=>$qty, ':id'=>$idb]);
        // insert detail
        $total = $harga * $qty;
        $insert->execute([':idpembeli'=>$idp, ':idbuku'=>$idb, ':qty'=>$qty, ':harga'=>$total]);
    }
    $pdo->commit();
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()]);
}
