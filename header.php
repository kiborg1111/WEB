<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="/kickzone/style/header.css">
<div class="header">
    <div class="header-container">
        <div class="logo">
            <a href="/kickzone/index.php">
                <img src="/kickzone/photo/logo.png" alt="KickZone">
            </a>
        </div>

        <nav>
            <ul>
                <li><a href="/kickzone/index.php#scroll-section">Новинки</a></li>
                <li><a href="/kickzone/catalog.php">Каталог</a></li>
                <li><a href="index.php">Мужское</a></li>
                <li><a href="index.php">Женское</a></li>
                <li><a href="index.php">О нас</a></li>
            </ul>
        </nav>
        <div class="header-icons">
            <a href="#" class="icon-link" aria-label="Поиск">
                <i class="fas fa-search"></i>
            </a>
            <a href="/kickzone/cart.php" class="icon-link" aria-label="Корзина">
                <i class="fas fa-shopping-bag"></i>
            </a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/kickzone/admin/index.php" class="icon-link" aria-label="Личный кабинет">
                        <img src="/kickzone/photo/anonymous.png" alt="Avatar" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                    </a>
                <?php else: ?>
                    <a href="/kickzone/account/profile.php" class="icon-link" aria-label="Личный кабинет">
                        <img src="/kickzone/photo/avatar.jpg" alt="Avatar" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="/kickzone/account/login.php" class="icon-link" aria-label="Личный кабинет">
                    <i class="fas fa-user-circle"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>