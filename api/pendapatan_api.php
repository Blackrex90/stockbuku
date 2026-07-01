<?php
// api/pendapatan_api.php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
secure_session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }

$range = $_GET['range'] ?? '12m'; // '12m' last 12 months or 'year' for yearly
try {
    if ($range === 'year') {
        // yearly totals
        $stmt = $pdo->prepare('SELECT YEAR(dp.created_at) as year, SUM(dp.harga) as total FROM detil_pembeli dp GROUP BY YEAR(dp.created_at) ORDER BY year');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        echo json_encode(['data'=>$rows]);
        exit;
    }
    // default last 12 months
    $stmt = $pdo->prepare("SELECT DATE_FORMAT(dp.created_at, '%Y-%m') as ym, SUM(dp.harga) as total FROM detil_pembeli dp WHERE dp.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY ym ORDER BY ym");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    echo json_encode(['data'=>$rows]);
} catch (Exception $e) {
    http_response_code(500); echo json_encode(['error'=>$e->getMessage()]);
}
