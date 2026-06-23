<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /kickzone/account/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Админ-панель' ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="admin-header">
    <div style="display: flex; align-items: center; gap: 20px;">
        <a href="/kickzone/index.php" class="back-home-btn">
            ←
        </a>
        <h1>Админ-панель</h1>
    </div>
    <div class="admin-user">
        <a href="/kickzone/account/logout.php" class="logout-btn">
            выйти
        </a>
    </div>
</div>

<div class="nav-links">
    <a href="index.php" <?= (basename($_SERVER['PHP_SELF']) == 'index.php' || basename($_SERVER['PHP_SELF']) == 'order_details.php') ? 'class="active"' : '' ?>>Заказы</a>
    <a href="products.php" <?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'class="active"' : '' ?>>Товары</a>
    <a href="categories.php" <?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'class="active"' : '' ?>>Категории</a>
</div>

<div class="container">