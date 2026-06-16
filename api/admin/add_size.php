<?php
session_start();
header('Content-Type: application/json');
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещён']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$value = trim($input['value'] ?? '');

if (empty($value)) {
    echo json_encode(['success' => false, 'message' => 'Размер не может быть пустым']);
    exit;
}

$sort_order = $conn->query("SELECT MAX(sort_order) as max FROM sizes")->fetch_assoc()['max'] + 1;
$stmt = $conn->prepare("INSERT INTO sizes (value, sort_order) VALUES (?, ?)");
$stmt->bind_param("si", $value, $sort_order);
$stmt->execute();

echo json_encode(['success' => true, 'id' => $conn->insert_id]);
?>