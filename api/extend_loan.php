<?php
// api/extend_loan.php
// Handle loan extension requests. Requires 'loans' table (see migration script)
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
secure_session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit;
}
if (empty($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
$csrf = $_POST['_csrf'] ?? ''; if (!validate_csrf($csrf)) { http_response_code(400); echo json_encode(['error'=>'Invalid CSRF']); exit; }

$loan_id = isset($_POST['loan_id']) ? (int)$_POST['loan_id'] : 0;
if (!$loan_id) { http_response_code(400); echo json_encode(['error'=>'Missing loan_id']); exit; }

try {
    // Check loan exists
    $q = $pdo->prepare('SELECT id, due_date, extended_times FROM loans WHERE id = :id FOR UPDATE');
    $q->execute([':id'=>$loan_id]);
    $loan = $q->fetch();
    if (!$loan) { http_response_code(404); echo json_encode(['error'=>'Loan not found']); exit; }

    // Prevent more than 1 extension or limit to business rule (for example max 1 extension)
    if ((int)$loan['extended_times'] >= 1) {
        echo json_encode(['error'=>'Maximum extensions reached']); exit;
    }

    // Validate can_extend logic: only allow if within 3 days before due
    $due = $loan['due_date'];
    if (!can_extend($due)) {
        echo json_encode(['error'=>'Extension not allowed at this time']); exit;
    }

    // Extend by 7 days
    $newDue = (new DateTime($due))->modify('+7 days')->format('Y-m-d H:i:s');

    $u = $pdo->prepare('UPDATE loans SET due_date = :newdue, extended_times = extended_times + 1 WHERE id = :id');
    $u->execute([':newdue'=>$newDue, ':id'=>$loan_id]);

    echo json_encode(['success'=>true, 'new_due' => $newDue]);
} catch (Exception $e) {
    http_response_code(500); echo json_encode(['error'=>$e->getMessage()]);
}
