<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

$category = null;
$is_edit = false;

if(isset($_GET['id'])) {
    $is_edit = true;
    $id = (int)$_GET['id'];

    $stmt = $conn -> prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();

    if(!$category) {
        header('Location: categories.php');
        exit;
    }
}

require_once 'templates/category_form.php';
?>