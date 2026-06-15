<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /kickzone/account/login.php');
    exit;
}

$product = null;
$is_edit = false;

if(isset($_GET['id'])) {
    $is_edit = true;
    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if(!$product) {
        header('Location: products.php');
        exit;
    }
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);

require_once 'templates/product_form.php';
?>