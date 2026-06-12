<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

if(isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];

    if($count > 0) {
        $_SESSION['error'] = "Нельзя удалить категорию, в которой есть товары";
    } else {
        $stmt = $conn->prepare("SELECT image FROM categories WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();

        if($category && $category['image']) {
            $image_path = '../uploads/categories/' . $category['image'];
            if(file_exists($image_path)){
                unlink($image_path);
            }
        }

        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
    }

    header('Location: categories.php');
    exit;
}

$categories = $conn->query("SELECT * FROM categories ORDER BY sort_order, name") -> fetch_all(MYSQLI_ASSOC);
require_once 'templates/categories.php'
?>