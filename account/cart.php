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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/kickzone/account_style/profile.css">
    <link rel="stylesheet" href="/kickzone/account_style/cart.css">
    <script src="/kickzone/script.js"></script>
</head>
<body>

<div class="cart-container">
    <div class="cart-header">
        <a href="#" onclick="history.back(); return false;" class="back-home-btn">
            <i class="fas fa-angle-left"></i>
        </a>
        <h2>Корзина</h2>
    </div>
    
    <div id="cartContent">
        <div class="loading">Загрузка...</div>
    </div>
</div>

<script>
    // Функция для получения корзины
    async function getCartItems() {
        try {
            const response = await fetch('/kickzone/api/cart.php', {
                headers: { 'Content-Type': 'application/json' }
            });
            
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Ошибка загрузки корзины:', error);
            return { success: false, cart: [] };
        }
    }

    // Загрузка корзины
    async function loadCart() {
        const container = document.getElementById('cartContent');
        
        try {
            const data = await getCartItems();
            
            if (!data.success || data.cart.length === 0) {
                container.innerHTML = `
                    <div class="empty-cart">
                        <div class="empty-cart-content">
                            <div class="empty-cart-image">
                                <img src="/kickzone/photo/cart.png" alt="Пустая корзина">
                            </div>
                        </div>
                    </div>
                `;
                return;
            }
            
            let html = `
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Кол-во</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            let total = 0;
            
            data.cart.forEach(item => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                
                html += `
                    <tr class="cart-item" data-cart-id="${item.id}" data-product-id="${item.product_id}">
                        <td>
                            <img src="/kickzone/uploads/products/${item.image}" class="cart-image" alt="${item.name}">
                        </td>
                        <td>${item.name}</td>
                        <td class="price">${Number(item.price).toLocaleString()} ₽</td>
                        <td>
                            <div class="quantity-control">
                                <button class="qty-btn minus" data-product-id="${item.product_id}">−</button>
                                <input type="number" class="qty-input" value="${item.quantity}" min="1" data-product-id="${item.product_id}">
                                <button class="qty-btn plus" data-product-id="${item.product_id}">+</button>
                            </div>
                        </td>
                        <td class="subtotal">${subtotal.toLocaleString()} ₽</td>
                        <td>
                            <button class="remove-btn" data-product-id="${item.product_id}">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            html += `
                    </tbody>
                </table>
                <div class="cart-total">
                    <span>Итого:</span>
                    <span class="total-price">${total.toLocaleString()} ₽</span>
                </div>
                <button id="checkoutBtn" class="btn-checkout">Оформить заказ</button>
            `;
            
            container.innerHTML = html;
            
            // Обработчики событий
            document.querySelectorAll('.qty-btn.minus').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const productId = btn.dataset.productId;
                    const input = document.querySelector(`.qty-input[data-product-id="${productId}"]`);
                    let qty = parseInt(input.value) - 1;
                    if (qty < 1) qty = 1;
                    input.value = qty;
                    await updateCartQuantity(productId, qty);
                    loadCart();
                });
            });
            
            document.querySelectorAll('.qty-btn.plus').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const productId = btn.dataset.productId;
                    const input = document.querySelector(`.qty-input[data-product-id="${productId}"]`);
                    let qty = parseInt(input.value) + 1;
                    input.value = qty;
                    await updateCartQuantity(productId, qty);
                    loadCart();
                });
            });
            
            document.querySelectorAll('.qty-input').forEach(input => {
                input.addEventListener('change', async () => {
                    const productId = input.dataset.productId;
                    let qty = parseInt(input.value);
                    if (qty < 1) qty = 1;
                    input.value = qty;
                    await updateCartQuantity(productId, qty);
                    loadCart();
                });
            });
            
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const productId = btn.dataset.productId;
                    if (confirm) {
                        await removeFromCart(productId);
                        loadCart();
                    }
                });
            });
            
            document.getElementById('checkoutBtn').addEventListener('click', async () => {
                try {
                    // 1. Сначала получаем данные пользователя из БД
                    const userResponse = await fetch('/kickzone/api/get_user_info.php');
                    const userData = await userResponse.json();
                    
                    let address = userData.address;
                    let phone = userData.phone;
                    
                    // 2. Если адрес или телефон не заполнены - запрашиваем
                    if (!address || address.trim() === '') {
                        const confirmRedirect = confirm('Адрес доставки обязателен!\nДобавишь прямо сейчас?');
                        if (confirmRedirect) {
                            window.location.href = '/kickzone/account/personal_data.php';
                        }
                        return;
                    }
                    
                    if (!phone || phone.trim() === '') {
                        const confirmRedirect = confirm('Номер телефона обязателен!\nДобавишь прямо сейчас?');
                        if (confirmRedirect) {
                            window.location.href = '/kickzone/account/personal_data.php';
                        }
                        return;
                    }
                    
                    const response = await fetch('/kickzone/api/checkout.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ 
                            address: address.trim(), 
                            phone: phone.trim()
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Заказ №' + data.order_number + ' успешно оформлен!');
                        window.location.href = '/kickzone/account/orders.php';
                    }
                } catch (error) {
                    console.error('Ошибка:', error);
                }
            });
        } catch (error) {
            container.innerHTML = '<div class="error">Ошибка загрузки корзины</div>';
            console.error('Ошибка:', error);
        }
    }
    
    // Обновление количества
    async function updateCartQuantity(productId, quantity) {
        try {
            await fetch('/kickzone/api/cart.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId, quantity })
            });
        } catch (error) {
            console.error('Ошибка обновления:', error);
        }
    }
    
    loadCart();
</script>

</body>
</html>