<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($action === 'update_info') {
    $email = trim($input['email'] ?? '');
    $full_name = trim($input['full_name'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $address = trim($input['address'] ?? '');

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email обязателен']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email уже занят']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET email = ?, full_name = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $email, $full_name, $phone, $address, $user_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Данные обновлены']);
}
elseif ($action === 'change_password') {
    $old_password = $input['old_password'] ?? '';
    $new_password = $input['new_password'] ?? '';

    if (empty($old_password) || empty($new_password) || strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Пароль должен быть не менее 6 символов']);
        exit;
    }

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!password_verify($old_password, $user['password_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Неверный старый пароль']);
        exit;
    }

    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmt->bind_param("si", $new_hash, $user_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Пароль изменён']);
}
else {
    echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
}
?>