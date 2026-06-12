<?php
session_start();
header('Content-Type: application/json');
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещён']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['order_id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Не хватает данных']);
    exit;
}

$allowed_statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
if (!in_array($data['status'], $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Недопустимый статус']);
    exit;
}

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $data['status'], $data['order_id']);
$stmt->execute();

echo json_encode(['success' => true, 'message' => 'Статус обновлён']);
?>