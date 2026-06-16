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

$value = strtolower(trim(preg_replace('/[^a-zа-яё0-9-]+/iu', '-', $name), '-'));

if (empty($value)) {
    $value = 'color-' . time();
}

$check = $conn->prepare("SELECT id FROM colors WHERE value = ?");
$check->bind_param("s", $value);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    $value = $value . '-' . time();
}

$stmt = $conn->prepare("INSERT INTO colors (name, value) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $value);
$stmt->execute();

echo json_encode(['success' => true, 'id' => $conn->insert_id]);
?>