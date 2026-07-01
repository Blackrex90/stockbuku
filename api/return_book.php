<?php
// api/return_book.php
// Process book return and calculate fine. Requires 'loans' table (see migration)
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
secure_session_start();
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }
if (empty($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
$csrf = $_POST['_csrf'] ?? ''; if (!validate_csrf($csrf)) { http_response_code(400); echo json_encode(['error'=>'Invalid CSRF']); exit; }

$loan_id = isset($_POST['loan_id']) ? (int)$_POST['loan_id'] : 0;
if (!$loan_id) { http_response_code(400); echo json_encode(['error'=>'Missing loan_id']); exit; }

try {
    $q = $pdo->prepare('SELECT id, due_date, returned_at FROM loans WHERE id = :id FOR UPDATE');
    $q->execute([':id'=>$loan_id]);
    $loan = $q->fetch();
    if (!$loan) { http_response_code(404); echo json_encode(['error'=>'Loan not found']); exit; }

    if ($loan['returned_at']) { echo json_encode(['error'=>'Already returned']); exit; }

    $now = (new DateTime())->format('Y-m-d H:i:s');
    $fine = calculate_fine($loan['due_date'], $now);

    // Update loan record
    $u = $pdo->prepare('UPDATE loans SET returned_at = :now, fine_amount = :fine WHERE id = :id');
    $u->execute([':now'=>$now, ':fine'=>$fine, ':id'=>$loan_id]);

    echo json_encode(['success'=>true, 'fine' => $fine, 'returned_at'=>$now]);
} catch (Exception $e) {
    http_response_code(500); echo json_encode(['error'=>$e->getMessage()]);
}
