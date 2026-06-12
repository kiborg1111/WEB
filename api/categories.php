<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$result = $conn->query("SELECT * FROM categories ORDER BY sort_order");
$categories = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'categories' => $categories]);
?>