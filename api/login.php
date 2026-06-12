<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if(empty($data['username']) || empty($data['password'])){
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $data['username'], $data['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if($user && password_verify($data['password'], $user['password_hash'])){
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    echo json_encode(['success' => true, 'user' => [
        'id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role']
    ]]);
} else {
    echo json_encode(['success' => false, 'message' => 'Неверный логин или пароль']);
}
?>