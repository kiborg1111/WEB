<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /kickzone/account/login.php');
    exit;
}

$title = 'Заказы';
include __DIR__ . '/header.php';
?>

<div class="content-card">
    <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <h2 class="section-title" style="margin: 0;">Все заказы</h2>
        <button id="refreshBtn" style="background: #f0f0f0; border: 2px solid black; border-radius: 8px; padding: 8px 12px; cursor: pointer; font-family: 'font1', sans-serif; transition: 0.2s;">
            🔄
        </button>
    </div>
    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Номер заказа</th>
                    <th>Пользователь</th>
                    <th>Сумма</th>
                    <th>Адрес</th>
                    <th>Статус</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody id="ordersBody">
                <tr>
                    <td colspan="7" style="text-align: center;">Загрузка...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let loadInterval = null;

async function loadOrders() {
    try {
        const response = await fetch('/kickzone/admin/api/get_orders.php');
        const data = await response.json();
        
        if (data.success) {
            const tbody = document.getElementById('ordersBody');
            
            if (data.orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Заказов пока нет</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.orders.map(order => `
                <tr style="height: 80px;" data-order-id="${order.id}">
                    <td style="vertical-align: middle;">#${order.id}</td>
                    <td style="vertical-align: middle;">
                        <a href="order_details.php?id=${order.id}" style="color: #007bff; text-decoration: none;">
                            ${order.order_number}
                        </a>
                    </td>
                    <td style="vertical-align: middle;">${order.username}</td>
                    <td style="vertical-align: middle;">${Number(order.total).toLocaleString()} ₽</td>
                    <td style="vertical-align: middle;">${order.address || '-'}</td>
                    <td style="vertical-align: middle;">
                        <select class="status-select" data-order-id="${order.id}">
                            <option value="pending" ${order.status == 'pending' ? 'selected' : ''}>Ожидает</option>
                            <option value="confirmed" ${order.status == 'confirmed' ? 'selected' : ''}>Подтверждён</option>
                            <option value="shipped" ${order.status == 'shipped' ? 'selected' : ''}>Отправлен</option>
                            <option value="delivered" ${order.status == 'delivered' ? 'selected' : ''}>Доставлен</option>
                            <option value="cancelled" ${order.status == 'cancelled' ? 'selected' : ''}>Отменён</option>
                        </select>
                    </td>
                    <td style="vertical-align: middle;">
                        ${new Date(order.created_at).toLocaleString('ru-RU')}
                        <span class="status-dot status-dot-${order.status}"></span>
                    </td>
                </tr>
            `).join('');
            
            document.querySelectorAll('.status-select').forEach(select => {
                select.removeEventListener('change', handleStatusChange);
                select.addEventListener('change', handleStatusChange);
            });
        }
    } catch (error) {
        console.error('Ошибка загрузки заказов:', error);
        document.getElementById('ordersBody').innerHTML = '<tr><td colspan="7" style="text-align: center; color: #dc3545;">Ошибка загрузки заказов</td></tr>';
    }
}

function handleStatusChange() {
    const orderId = this.dataset.orderId;
    const newStatus = this.value;
    const row = this.closest('tr');
    const dot = row.querySelector('.status-dot');
    
    this.disabled = true;
    
    fetch('/kickzone/api/admin/update_order_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: orderId, status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (dot) {
                dot.className = 'status-dot status-dot-' + newStatus;
            }
            this.disabled = false;
        } else {
            alert('Ошибка: ' + data.message);
            this.value = this.querySelector(`option[value="${newStatus}"]`).value;
            this.disabled = false;
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        alert('Ошибка соединения с сервером');
        this.disabled = false;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    loadInterval = setInterval(loadOrders, 5000);
    
    document.getElementById('refreshBtn').addEventListener('click', function() {
        loadOrders();
    });
});
</script>

</body>
</html>