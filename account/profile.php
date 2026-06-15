<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /kickzone/account/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой профиль - KickZone</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; text-align: center; padding: 50px; }
        .card { background: white; border: 2px solid black; border-radius: 15px; padding: 30px; max-width: 500px; margin: 0 auto; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #ff6ab5; border: 2px solid black; border-radius: 8px; text-decoration: none; color: black; }
        .logout-btn { background: transparent; margin-left: 10px; }
    </style>
</head>
<body>
<div class="card">
    <h1>👤 Добро пожаловать, <?= htmlspecialchars($username) ?>!</h1>
    <p>ID: <?= $user_id ?></p>
    <p>Это ваш личный кабинет</p>
    <a href="/kickzone/catalog.php" class="btn">🛍️ Каталог</a>
    <a href="logout.php" class="btn logout-btn">🚪 Выйти</a>
</div>
</body>
</html>