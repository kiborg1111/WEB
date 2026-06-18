<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /kickzone/account/login.php');
    exit();
}

$username = $_SESSION['username'];
?>

<button class="profile-toggle" id="profileToggle" aria-label="Меню профиля">
    <i class="fas fa-bars"></i>
</button>
<div class="profile-sidebar-overlay" id="profileOverlay"></div>

<div class="profile-sidebar" id="profileSidebar">
    <div class="profile-user">
        <div class="profile-avatar">
            <img src="/kickzone/photo/avatar.jpg" alt="Avatar">
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
        </ul>
    </nav>

    <div class="profile-logout">
        <a href="logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt"></i>
            <span>Выйти</span>
        </a>
    </div>
</div>