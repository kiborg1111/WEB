<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$gender = isset($_GET['gender']) ? $_GET['gender'] : 'all';

$sql = "SELECT p.*, 
            c.name as category_name, 
            col.name as color, 
            b.name as brand,
            GROUP_CONCAT(DISTINCT s.value ORDER BY s.sort_order SEPARATOR ', ') as sizes
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN colors col ON p.color_id = col.id
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN product_sizes ps ON p.id = ps.product_id
        LEFT JOIN sizes s ON ps.size_id = s.id";

if ($gender !== 'all') {
    $sql .= " WHERE p.gender = '" . $conn->real_escape_string($gender) . "'";
}

$sql .= " GROUP BY p.id";

$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode(['success' => true, 'products' => $products]);
?>