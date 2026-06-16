<?php
session_start();
header('Content-Type: application/json');
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещён']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$name = trim($input['name'] ?? '');

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Название не может быть пустым']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO brands (name) VALUES (?)");
$stmt->bind_param("s", $name);
$stmt->execute();

echo json_encode(['success' => true, 'id' => $conn->insert_id]);
?>