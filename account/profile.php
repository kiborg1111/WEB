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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/kickzone/account_style/profile.css">
    <link rel="stylesheet" href="/kickzone/account_style/profile_header.css">
</head>
<body>
    <a href="/kickzone/index.php" class="back-home-btn">
        <i class="fas fa-angle-left"></i> 
    </a>
    <?php include 'profile_header.php'; ?>

    <div class="main-content">
        <div style="display: flex; justify-content: center; align-items: center; height: 80vh;">
            <img src="/kickzone/photo/inekemre.gif" style="width: 100%; max-width: 800px; height: auto; border-radius: 12px;">
        </div>
    </div>
</body>
</html>