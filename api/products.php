<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$result = $conn->query("SELECT p.*, 
                            c.name as category_name, 
                            col.name as color, 
                            b.name as brand,
                            s.value as size_value
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        LEFT JOIN colors col ON p.color_id = col.id 
                        LEFT JOIN brands b ON p.brand_id = b.id 
                        LEFT JOIN sizes s ON p.size_id = s.id");

$products = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode(['success' => true, 'products' => $products]);
?>