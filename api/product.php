<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!$id) {
    echo json_encode(['success' => false, 'message' => 'ID товара не указан']);
    exit;
}

$stmt = $conn->prepare("SELECT p.*, c.name as category_name
                        FROM products p
                        LEFT JOIN categories c ON p.category_id = c.id
                        WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if(!$product) {
    echo json_encode(['success' => false, 'message' => 'Товар не найден']);
    exit;
}

echo json_encode(['success' => true, 'product' => $product]);
?>