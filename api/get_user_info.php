<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'success' => true,
    'phone' => $user['phone'] ?? '',
    'address' => $user['address'] ?? ''
]);
?>