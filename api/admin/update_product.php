<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /kickzone/account/login.php');
    exit;
}

$is_edit = isset($_POST['id']) && !empty($_POST['id']);

$name = trim($_POST['name'] ?? '');
$gender = $_POST['gender'] ?? 'unisex';
$category_id = (int)($_POST['category_id'] ?? 0);
$slug = trim($_POST['slug'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$brand_id = (int)($_POST['brand_id'] ?? 0);
$color_id = (int)($_POST['color_id'] ?? 0);

if (empty($slug) || $slug === '' || $slug === '0') {
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $name), '-'));
}

if (empty($slug) || $slug === '0') {
    $slug = 'product-' . time();
}

if ($is_edit) {
    $id = (int)$_POST['id'];
    $check = $conn->prepare("SELECT id FROM products WHERE slug = ? AND id != ?");
    $check->bind_param("si", $slug, $id);
} else {
    $check = $conn->prepare("SELECT id FROM products WHERE slug = ?");
    $check->bind_param("s", $slug);
}
$check->execute();
if ($check->get_result()->num_rows > 0) {
    $slug = $slug . '-' . time();
}

$image_name = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../../uploads/products/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
}

if ($is_edit) {
    $id = (int)$_POST['id'];

    if ($image_name) {
        $stmt = $conn->prepare("UPDATE products SET name=?, gender=?, category_id=?, slug=?, description=?, price=?, image=?, brand_id=?, color_id=? WHERE id=?");
        $stmt->bind_param("ssissdsiii", $name, $gender, $category_id, $slug, $description, $price, $image_name, $brand_id, $color_id, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, gender=?, category_id=?, slug=?, description=?, price=?, brand_id=?, color_id=? WHERE id=?");
        $stmt->bind_param("ssissdiii", $name, $gender, $category_id, $slug, $description, $price, $brand_id, $color_id, $id);
    }
    $stmt->execute();
    $product_id = $id;
} else {
    $stmt = $conn->prepare("INSERT INTO products (name, gender, category_id, slug, description, price, image, brand_id, color_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissdsii", $name, $gender, $category_id, $slug, $description, $price, $image_name, $brand_id, $color_id);
    $stmt->execute();
    $product_id = $conn->insert_id;
}

if ($product_id) {
    $conn->query("DELETE FROM product_sizes WHERE product_id = $product_id");
    
    $size_ids = $_POST['size_ids'] ?? [];
    $stocks = $_POST['stocks'] ?? [];
    
    for ($i = 0; $i < count($size_ids); $i++) {
        if (!empty($size_ids[$i])) {
            $size = (int)$size_ids[$i];
            $stock = (int)($stocks[$i] ?? 0);
            
            $stmt = $conn->prepare("INSERT INTO product_sizes (product_id, size_id, stock) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $product_id, $size, $stock);
            $stmt->execute();
        }
    }
}

header('Location: /kickzone/admin/products.php');
exit;
?>