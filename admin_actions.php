<?php
// admin_actions.php
// CRUD actions for admin management (add/edit/delete)
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/helpers.php';
secure_session_start();

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$action = $_POST['action'] ?? '';
$csrf = $_POST['_csrf'] ?? '';
if (!validate_csrf($csrf)) { http_response_code(400); echo json_encode(['error'=>'Invalid CSRF']); exit; }

try {
    if ($action === 'add') {
        $username = validate_input($_POST['username'] ?? '');
        $email = validate_input($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$username || !$email || !$password) throw new Exception('Missing fields');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception('Invalid email');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO login (username, email, password, role) VALUES (:u,:e,:p,:r)');
        $stmt->execute([':u'=>$username, ':e'=>$email, ':p'=>$hash, ':r'=>'admin']);
        echo json_encode(['success'=>true]); exit;
    }

    if ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $username = validate_input($_POST['username'] ?? '');
        $email = validate_input($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$id || !$username || !$email) throw new Exception('Missing fields');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception('Invalid email');
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE login SET username=:u, email=:e, password=:p WHERE iduser = :id');
            $stmt->execute([':u'=>$username, ':e'=>$email, ':p'=>$hash, ':id'=>$id]);
        } else {
            $stmt = $pdo->prepare('UPDATE login SET username=:u, email=:e WHERE iduser = :id');
            $stmt->execute([':u'=>$username, ':e'=>$email, ':id'=>$id]);
        }
        echo json_encode(['success'=>true]); exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) throw new Exception('Missing id');
        $stmt = $pdo->prepare('DELETE FROM login WHERE iduser = :id');
        $stmt->execute([':id'=>$id]);
        echo json_encode(['success'=>true]); exit;
    }

    http_response_code(400); echo json_encode(['error'=>'Unknown action']);
} catch (Exception $e) {
    http_response_code(500); echo json_encode(['error'=>$e->getMessage()]);
}
