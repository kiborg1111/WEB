<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$gender = isset($_GET['gender']) ? $_GET['gender'] : 'all';
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

$sql = "SELECT p.*, 
            c.name as category_name, 
            c.id as category_id,
            col.name as color, 
            col.id as color_id,
            b.name as brand,
            b.id as brand_id,
            GROUP_CONCAT(DISTINCT s.value ORDER BY s.sort_order SEPARATOR ', ') as sizes
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN colors col ON p.color_id = col.id
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN product_sizes ps ON p.id = ps.product_id
        LEFT JOIN sizes s ON ps.size_id = s.id
        WHERE 1=1";

if ($gender !== 'all') {
    $sql .= " AND p.gender = '" . $conn->real_escape_string($gender) . "'";
}

if ($category_id > 0) {
    $sql .= " AND p.category_id = " . $category_id;
}

$sql .= " GROUP BY p.id";

$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);

$categories_result = $conn->query("SELECT id, name FROM categories ORDER BY sort_order, name");
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'success' => true, 
    'products' => $products,
    'categories' => $categories
]);
?>