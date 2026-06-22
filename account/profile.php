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
    <a href="#" onclick="history.back(); return false;" class="back-home-btn">
        <i class="fas fa-angle-left" aria-label="Назад"></i> 
    </a>
    
    <?php include 'profile_header.php'; ?>

    <div class="profile-gif-container">
        <div class="gif-wrapper">
            <div class="tenor-gif-embed" data-postid="18074765" data-share-method="host" data-aspect-ratio="1" data-width="100%">
                <a href="https://tenor.com/view/go-vote-election-election2020-democratic-republican-gif-18074765"></a>
                from <a href="https://tenor.com/search/go+vote-stickers"></a>
            </div>
        </div>
    </div>

    <script type="text/javascript" async src="https://tenor.com/embed.js"></script>
</body>
</html>