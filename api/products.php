<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$result = $conn->query("SELECT p.*, c.name as category_name
                        FROM products p
                        LEFT JOIN categories c ON p.category_id = c.id
                        ORDER BY p.created_at DESC");

$products = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode(['success' => true, 'products' => $products]);
?>