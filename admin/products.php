<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /kickzone/account/login.php');
    exit;
}

if(isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if($product && $product['image']) {
        $image_path = '../uploads/products/' . $product['image'];
        if(file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    header('Location: products.php');
    exit;
}

$stmt = $conn->prepare("SELECT p.*, 
                            c.name as category_name, 
                            col.name as color, 
                            b.name as brand,
                            s.value as size
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        LEFT JOIN colors col ON p.color_id = col.id 
                        LEFT JOIN brands b ON p.brand_id = b.id 
                        LEFT JOIN sizes s ON p.size_id = s.id 
                        ORDER BY p.id DESC");

$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);

require_once 'templates/products.php';
?>