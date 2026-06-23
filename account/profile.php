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
    <style>
        .profile-gif-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 20px;
            margin-right: 280px;
        }

        .gif-wrapper {
            max-width: 400px;
            width: 100%;
        }

        .gif-wrapper img {
            width: 100%;
            height: auto;
            display: block;
        }

        @media (max-width: 1024px) {
            .profile-gif-container {
                margin-right: 240px;
            }
        }

        @media (max-width: 768px) {
            .profile-gif-container {
                margin-right: 0;
                min-height: 50vh;
                padding: 40px 20px;
                justify-content: flex-start;
                padding-left: 10px;
            }

            .gif-wrapper {
                max-width: 300px;
            }
        }

        @media (max-width: 480px) {
            .profile-gif-container {
                padding-left: 5px;
                min-height: 40vh;
                justify-content: flex-start;
            }

            .gif-wrapper {
                max-width: 220px;
            }
        }
    </style>
</head>
<body>
    <a href="#" onclick="history.back(); return false;" class="back-home-btn">
        ←
    </a>
    
    <?php include 'profile_header.php'; ?>

    <div class="profile-gif-container">
        <div class="gif-wrapper">
            <img src="/kickzone/photo/inekemre.gif" alt="cow dancing">
        </div>
    </div>
</body>
</html>