<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /kickzone/account/login.php');
    exit();
}

require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Получаем все заказы пользователя
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$all_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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

        <!-- Фильтры -->
        <div class="orders-filters">
            <button class="filter-btn active" data-filter="active">Активные</button>
            <button class="filter-btn" data-filter="completed">Завершенные</button>
        </div>

        <!-- Разделительная линия -->
        <div class="filter-divider"></div>

        <!-- Список заказов -->
        <div id="ordersList">
            <?php if (empty($all_orders)): ?>
            <?php else: ?>
                <?php foreach ($all_orders as $order): 
                    $is_active = in_array($order['status'], ['pending', 'confirmed', 'shipped']);
                    $is_completed = in_array($order['status'], ['delivered', 'cancelled']);
                    
                    if ($is_active) {
                        $status_class = 'active';
                    } elseif ($is_completed) {
                        $status_class = 'completed';
                    } else {
                        $status_class = 'other';
                    }
                ?>
                    <div class="order-card" data-status="<?= $status_class ?>">
                        <div class="order-header">
                            <div class="order-info">
                                <span class="order-number">Заказ №<?= htmlspecialchars($order['order_number']) ?></span>
                                <span class="order-date"><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></span>
                            </div>
                            <span class="order-status status-<?= $order['status'] ?>">
                                <?= $order['status'] ?>
                            </span>
                        </div>
                        <div class="order-body">
                            <div class="order-details">
                                <div class="order-detail">
                                    <span class="detail-label">Cумма:</span>
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
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const orderCards = document.querySelectorAll('.order-card');
        const emptyOrders = document.querySelector('.empty-orders');
        
        if (orderCards.length > 0 && emptyOrders) {
            emptyOrders.style.display = 'none';
        }
        
        function filterOrders(filter) {
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
            
            // Показываем или скрываем сообщение "Нет заказов"
            if (emptyOrders) {
                if (filter === 'active') {
                    const activeCards = document.querySelectorAll('.order-card[data-status="active"]');
                    emptyOrders.style.display = activeCards.length === 0 ? 'block' : 'none';
                } else if (filter === 'completed') {
                    const completedCards = document.querySelectorAll('.order-card[data-status="completed"]');
                    emptyOrders.style.display = completedCards.length === 0 ? 'block' : 'none';
                } else {
                    emptyOrders.style.display = 'none';
                }
            }
        }
        
        // Обработчики кнопок
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                filterOrders(filter);
            });
        });
        
        // При загрузке показываем активные заказы
        filterOrders('active');
    });
</script>

</body>
</html>