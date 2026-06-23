<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

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
                <li><a href="/kickzone/catalog.php?gender=all">Каталог</a></li>
                <li><a href="/kickzone/catalog.php?gender=male">Мужское</a></li>
                <li><a href="/kickzone/catalog.php?gender=female">Женское</a></li>
                <li><a href="/kickzone/about.php">О нас</a></li>
            </ul>
        </nav>

        <div class="header-icons">
            <button class="menu-toggle" id="menuToggle" aria-label="Меню">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="icons-wrapper" id="iconsWrapper">
                <a href="/kickzone/account/cart.php" class="icon-link" aria-label="Корзина">
                    🛒
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
                        👤
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const iconsWrapper = document.getElementById('iconsWrapper');
    
    if (menuToggle && iconsWrapper) {
        menuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            iconsWrapper.classList.toggle('active');
        });
    }
});
</script>