<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data['username']) || empty($data['email']) || empty($data['password'])){
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $data['username'], $data['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0){
    echo json_encode(['success' => false, 'message' => 'Пользователь с таким логином или email уже существует']);
    exit;
}

$hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $data['username'], $data['email'], $hashed_password);

if ($stmt->execute()){
    echo json_encode(['success' => true, 'message' => 'Регистрация прошла успешно']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при регистрации']);
}
?>