<?php
// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: /kickzone/account/login.php');
    exit();
}

$username = $_SESSION['username'];
?>

<div class="profile-sidebar">
    <div class="profile-user">
        <div class="profile-avatar">
            <img src = "/kickzone/photo/avatar.jpg" alt = "Avatar">
        </div>
        <div class="profile-name">
            <h3><?= htmlspecialchars($username) ?></h3>
        </div>
    </div>
    
    <nav class="profile-nav">
        <ul>
            <li>
                <a href="personal_data.php" class="profile-link">
                    <i class="fas fa-user"></i>
                    <span>Личные данные</span>
                </a>
            </li>
            <li>
                <a href="orders.php" class="profile-link">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Заказы</span>
                </a>
            </li>
            <li>
                <a href="favorites.php" class="profile-link">
                    <i class="fas fa-heart"></i>
                    <span>Избранное</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="profile-logout">
        <a href="logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt"></i>
            <span>Выйти</span>
        </a>
    </div>
</div>