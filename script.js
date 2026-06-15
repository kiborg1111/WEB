const API_URL = '/api/';

async function getProducts() {
    const response = await fetch(API_URL + 'products.php');
    const data = await response.json();
    return data.success ? data.products : [];
}

async function addToCart(productId, quantity = 1) {
    const response = await fetch(API_URL + 'cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    });
    return await response.json();
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

async function updateProfile(username, email) {
    const response = await fetch('/api/update_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'update_info', username, email })
    });
    return await response.json();
}

async function changePassword(old_password, new_password) {
    const response = await fetch('/api/update_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'change_password', old_password, new_password })
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