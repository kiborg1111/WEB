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
                <li><a href="/kickzone/catalog.php?gender=all">Каталог</a></li>
                <li><a href="/kickzone/catalog.php?gender=male">Мужское</a></li>
                <li><a href="/kickzone/catalog.php?gender=female">Женское</a></li>
                <li><a href="/kickzone/about.php">О нас</a></li>
            </ul>
        </nav>

        <div class="header-icons">
        

            <a href="/kickzone/account/cart.php" class="icon-link" aria-label="Корзина">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchToggle = document.getElementById('searchToggle');
    const searchDropdown = document.getElementById('searchDropdown');
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const suggestions = document.getElementById('searchSuggestions');

    let isOpen = false;
    let searchTimeout = null;

    searchToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        isOpen = !isOpen;
        searchDropdown.classList.toggle('active', isOpen);
        if (isOpen) {
            searchInput.focus();
            searchInput.value = '';
            suggestions.innerHTML = '';
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchDropdown.contains(e.target) && e.target !== searchToggle && !searchToggle.contains(e.target)) {
            searchDropdown.classList.remove('active');
            isOpen = false;
        }
    });

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            suggestions.innerHTML = '';
            return;
        }

        searchTimeout = setTimeout(async function() {
            try {
                const response = await fetch('/kickzone/api/products.php?search=' + encodeURIComponent(query));
                const data = await response.json();

                if (data.success && data.products.length > 0) {
                    const limited = data.products.slice(0, 5);
                    suggestions.innerHTML = limited.map(p => `
                        <a href="/kickzone/product-card.php?id=${p.id}" class="suggestion-item">
                            <img src="/kickzone/uploads/products/${p.image || 'placeholder.jpg'}" alt="${p.name}">
                            <div class="suggestion-info">
                                <div class="suggestion-name">${p.name}</div>
                                <div class="suggestion-price">${Number(p.price).toLocaleString()} ₽</div>
                            </div>
                        </a>
                    `).join('');
                } else {
                    suggestions.innerHTML = '<div class="suggestion-empty">Ничего не найдено</div>';
                }
            } catch (error) {
                console.error('Ошибка поиска:', error);
            }
        }, 300);
    });

    searchForm.addEventListener('submit', function(e) {
        const query = searchInput.value.trim();
        if (!query || query.length < 2) {
            e.preventDefault();
            showNotification('Введите минимум 2 символа для поиска', 'error');
            return;
        }
        searchDropdown.classList.remove('active');
        isOpen = false;
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isOpen) {
            searchDropdown.classList.remove('active');
            isOpen = false;
        }
    });
});

function showNotification(message, type = 'success') {
    const notification = document.getElementById('cartNotification');
    if (notification) {
        const messageEl = document.getElementById('notificationMessage');
        notification.classList.remove('success', 'error', 'show', 'hide');
        notification.classList.add(type);
        messageEl.textContent = message;
        notification.classList.add('show');

        clearTimeout(window.notificationTimeout);
        window.notificationTimeout = setTimeout(() => {
            notification.classList.remove('show');
            notification.classList.add('hide');
        }, 3000);
    } else {
        alert(message);
    }
}
</script>