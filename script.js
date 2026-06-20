const API_URL = '/kickzone/api/';

async function getProducts() {
    const response = await fetch(API_URL + 'products.php');
    const data = await response.json();
    return data.success ? data.products : [];
}

async function addToCart(productId, quantity = 1) {
    try {
        const response = await fetch('/kickzone/api/cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Товар добавлен в корзину', 'success');
        } else {
            showNotification(data.message, 'error');
        }
        
        return data;
    } catch (error) {
        console.error('Ошибка:', error);
        showNotification('Ошибка при добавлении', 'error');
    }
}

async function getCart() {
    const response = await fetch(API_URL + 'cart.php');
    return await response.json();
}

async function removeFromCart(productId) {
    const response = await fetch(API_URL + 'cart.php?product_id=' + productId, {
        method: 'DELETE'
    });
    return await response.json();
}

async function updateCartItem(productId, quantity) {
    const response = await fetch(API_URL + 'cart.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    });
    return await response.json();
}

async function getOrders() {
    const response = await fetch('/api/orders.php');
    return await response.json();
}

async function updateProfile(email, full_name, phone, address) {
    const response = await fetch('/kickzone/api/update_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'update_info',
            email,
            full_name,
            phone,
            address
        })
    });
    return await response.json();
}

async function changePassword(old_password, new_password) {
    const response = await fetch('/kickzone/api/update_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'change_password',
            old_password,
            new_password
        })
    });
    return await response.json();
}

async function getFavorites() {
    const response = await fetch('/api/favorites.php');
    return await response.json();
}

async function addToFavorites(productId) {
    const response = await fetch('/api/favorites.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    });
    return await response.json();
}

async function removeFromFavorites(productId) {
    const response = await fetch(`/api/favorites.php?product_id=${productId}`, {
        method: 'DELETE'
    });
    return await response.json();
}

async function checkAuth() {
    const response = await fetch('/api/auth.php');
    return await response.json();
}

async function searchProducts(query) {
    try {
        const response = await fetch('/kickzone/api/products.php?search=' + encodeURIComponent(query));
        const data = await response.json();
        return data.success ? data.products : [];
    } catch (error) {
        console.error('Ошибка поиска:', error);
        return [];
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const profileToggle = document.getElementById('profileToggle');
    const profileSidebar = document.getElementById('profileSidebar');
    const profileOverlay = document.getElementById('profileOverlay');

    if (profileToggle && profileSidebar && profileOverlay) {
        if (window.innerWidth <= 768) {
            profileToggle.style.display = 'flex';
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                profileToggle.style.display = 'flex';
            } else {
                profileToggle.style.display = 'none';
                profileSidebar.classList.remove('active');
                profileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        profileToggle.addEventListener('click', function() {
            profileSidebar.classList.toggle('active');
            profileOverlay.classList.toggle('active');
            document.body.style.overflow = profileSidebar.classList.contains('active') ? 'hidden' : '';
        });

        profileOverlay.addEventListener('click', function() {
            profileSidebar.classList.remove('active');
            profileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
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
    }
}