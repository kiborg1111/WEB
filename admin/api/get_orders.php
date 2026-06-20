<?php
session_start();
header('Content-Type: application/json');
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещён']);
    exit;
}

$stmt = $conn->prepare("SELECT o.*, u.username, u.email 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        ORDER BY o.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'orders' => $orders]);
?>