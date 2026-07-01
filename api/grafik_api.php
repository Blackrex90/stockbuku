<?php
// api/grafik_api.php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
secure_session_start();
header('Content-Type: application/json; charset=utf-8');
if (empty($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }

try {
    // Top 10 popular books by qty sold
    $stmt = $pdo->prepare('SELECT b.judulbuku, SUM(dp.qty) AS total_qty FROM detil_pembeli dp JOIN buku b ON dp.idbuku = b.idbuku GROUP BY b.idbuku ORDER BY total_qty DESC LIMIT 10');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    echo json_encode(['data'=>$rows]);
} catch (Exception $e) {
    http_response_code(500); echo json_encode(['error'=>$e->getMessage()]);
}
