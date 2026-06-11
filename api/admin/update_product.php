<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

$is_edit = isset($_POST['id']) && !empty($_POST['id']);

$name = $_POST['name'] ?? '';
$category_id = (int)$_POST['category_id'] ?? 0;
$slug = $_POST['slug'] ?? '';
$description = $_POST['description'] ?? '';
$price = (float)$_POST['price'] ?? 0;
$stock = (int)$_POST['stock'] ?? 0;

if(empty($slug)) {
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $name), '-'));
}

$image_name = null;
if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../../uploads/products/';
    if(!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = uniqid() . '.' . $ext;
    $upload_path = $upload_dir . $image_name;

    move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
}

if($is_edit) {
    $id = (int)$_POST['id'];

    if($image_name) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, category_id = ?, slug = ?, description = ?, price = ?, stock = ?, image = ?
                                WHERE id = ?");
        $stmt->bind_param("sisssdsi", $name, $category_id, $slug, $description, $price, $stock, $image_name, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, category_id = ?, slug = ?, description = ?, price = ?, stock = ?
                                WHERE id = ?");
        $stmt->bind_param("sisssdi", $name, $category_id, $slug, $description, $price, $stock, $id);
    }
    $stmt->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO products (name, category_id, slug, description, price, stock, image)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssds", $name, $category_id, $slug, $description, $price, $stock, $image_name);
    $stmt->execute();
}

header('Location: /kickzone/admin/products.php');
exit;
?>