<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit;
}

$is_edit = isset($_POST['id']) && !empty($_POST['id']);

$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
$description = trim($_POST['description'] ?? '');
$sort_order = (int)($_POST['sort_order'] ?? 0);

if (empty($name)) {
    header('Location: ../admin/categories.php');
    exit;
}

if (empty($slug)) {
    $slug = strtolower(trim(preg_replace('/[^a-z0-9-]+/', '-', strtolower($name)), '-'));
}

$image_name = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../uploads/categories/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
}

$check = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
$check->bind_param("s", $slug);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $slug = $slug . '-' . time();
}

if ($is_edit) {
    $id = (int)$_POST['id'];
    if ($image_name) {
        $stmt = $conn->prepare("UPDATE categories SET name=?, slug=?, description=?, sort_order=?, image=? WHERE id=?");
        $stmt->bind_param("sssisi", $name, $slug, $description, $sort_order, $image_name, $id);
    } else {
        $stmt = $conn->prepare("UPDATE categories SET name=?, slug=?, description=?, sort_order=? WHERE id=?");
        $stmt->bind_param("sssii", $name, $slug, $description, $sort_order, $id);
    }
    $stmt->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO categories (name, slug, description, sort_order, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $name, $slug, $description, $sort_order, $image_name);
    $stmt->execute();
}

header('Location: categories.php');
exit;
?>