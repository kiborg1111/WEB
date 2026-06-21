<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /kickzone/account/login.php');
    exit();
}

require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$all_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

function getStatusLabel($status) {
    $labels = [
        'pending' => 'Ожидает',
        'confirmed' => 'Подтверждён',
        'shipped' => 'Отправлен',
        'delivered' => 'Доставлен',
        'cancelled' => 'Отменён'
    ];
    return $labels[$status] ?? $status;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заказы - KickZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/kickzone/account_style/profile.css">
    <link rel="stylesheet" href="/kickzone/account_style/profile_header.css">
    <link rel="stylesheet" href="/kickzone/account_style/orders.css">
</head>
<body>

<a href="/kickzone/index.php" class="back-home-btn">
    <i class="fas fa-angle-left"></i>
</a>

<?php include 'profile_header.php'; ?>

<div class="main-content">
    <div class="orders-container">
        <div class="orders-header">
            <div class="orders-header-content">
                <h2>Мои заказы</h2>
            </div>
        </div>

        <div class="orders-filters">
            <button class="filter-btn active" data-filter="active">Активные</button>
            <button class="filter-btn" data-filter="completed">Завершенные</button>
        </div>

        <div class="filter-divider"></div>

        <div id="ordersList">
            <?php if (empty($all_orders)): ?>
                <div class="empty-orders" style="text-align: center; padding: 50px 0; font-family: 'font1', sans-serif; font-size: 18px; color: #999;">
                    У вас пока нет заказов
                </div>
            <?php else: ?>
                <?php foreach ($all_orders as $order): 
                    $is_active = in_array($order['status'], ['pending', 'confirmed', 'shipped']);
                    $is_completed = in_array($order['status'], ['delivered', 'cancelled']);
                    $status_class = $is_active ? 'active' : ($is_completed ? 'completed' : 'other');
                ?>
                    <div class="order-card" data-status="<?= $status_class ?>" data-order-id="<?= $order['id'] ?>">
                        <div class="order-header">
                            <div class="order-info">
                                <a href="order_details.php?id=<?= $order['id'] ?>" style="text-decoration: none; color: inherit;">
                                    <span class="order-number">Заказ №<?= htmlspecialchars($order['order_number']) ?></span>
                                </a>
                                <span class="order-date"><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></span>
                            </div>
                            <span class="order-status status-<?= $order['status'] ?>" id="status-<?= $order['id'] ?>">
                                <?= getStatusLabel($order['status']) ?>
                            </span>
                        </div>
                        <div class="order-body">
                            <div class="order-details">
                                <div class="order-detail">
                                    <span class="detail-label">Сумма:</span>
                                    <span class="detail-value"><?= number_format($order['total'], 0, '', ' ') ?> ₽</span>
                                </div>
                                <div class="order-detail">
                                    <span class="detail-label">Телефон:</span>
                                    <span class="detail-value"><?= htmlspecialchars($order['phone'] ?? '-') ?></span>
                                </div>
                                <div class="order-detail">
                                    <span class="detail-label">Адрес:</span>
                                    <span class="detail-value"><?= htmlspecialchars($order['address'] ?? '-') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    let loadInterval = null;
    let isLive = true;

    async function updateOrderStatuses() {
        const orderCards = document.querySelectorAll('.order-card');
        
        if (orderCards.length === 0) return;
        
        for (const card of orderCards) {
            const orderId = card.dataset.orderId;
            if (!orderId) continue;
            
            try {
                const response = await fetch('/kickzone/api/get_order_status.php?order_id=' + orderId);
                const data = await response.json();
                
                if (data.success) {
                    const statusSpan = document.getElementById('status-' + orderId);
                    if (statusSpan) {
                        const labels = {
                            'pending': 'Ожидает',
                            'confirmed': 'Подтверждён',
                            'shipped': 'Отправлен',
                            'delivered': 'Доставлен',
                            'cancelled': 'Отменён'
                        };
                        statusSpan.className = 'order-status status-' + data.status;
                        statusSpan.textContent = labels[data.status] || data.status;
                    }
                    
                    const isActive = ['pending', 'confirmed', 'shipped'].includes(data.status);
                    const isCompleted = ['delivered', 'cancelled'].includes(data.status);
                    
                    if (isActive) {
                        card.dataset.status = 'active';
                    } else if (isCompleted) {
                        card.dataset.status = 'completed';
                    } else {
                        card.dataset.status = 'other';
                    }
                    
                    const activeFilter = document.querySelector('.filter-btn.active');
                    if (activeFilter) {
                        filterOrders(activeFilter.dataset.filter);
                    }
                }
            } catch (error) {
                console.error('Ошибка обновления статуса заказа:', error);
            }
        }
    }

    function filterOrders(filter) {
        const orderCards = document.querySelectorAll('.order-card');
        const emptyOrders = document.querySelector('.empty-orders');
        let hasVisible = false;
        
        orderCards.forEach(card => {
            const status = card.dataset.status;
            if (filter === 'all' || status === filter) {
                card.style.display = 'block';
                hasVisible = true;
            } else {
                card.style.display = 'none';
            }
        });
        
        if (emptyOrders) {
            emptyOrders.style.display = hasVisible ? 'none' : 'block';
        }
    }

    function toggleLive() {
        if (isLive) {
            clearInterval(loadInterval);
            isLive = false;
        } else {
            isLive = true;
            updateOrderStatuses();
            loadInterval = setInterval(updateOrderStatuses, 5000);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterOrders(this.dataset.filter);
            });
        });
        
        setTimeout(updateOrderStatuses, 500);
        loadInterval = setInterval(updateOrderStatuses, 5000);
        filterOrders('active');
    });
</script>

</body>
</html>