<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /kickzone/account/login.php');
    exit();
}

require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$order_id) {
    header('Location: orders.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: orders.php');
    exit();
}

$stmt = $conn->prepare("SELECT oi.*, p.image 
                        FROM order_items oi 
                        LEFT JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$username = $_SESSION['username'];

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
    <title>Детали заказа - KickZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/kickzone/account_style/profile.css">
    <link rel="stylesheet" href="/kickzone/account_style/profile_header.css">
    <link rel="stylesheet" href="/kickzone/account_style/orders.css">
    <style>
        .order-detail-page .profile-toggle {
            display: none !important;
        }

        .order-detail-page .back-home-btn {
            display: none !important;
        }

        .order-detail-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .order-detail-header h2 {
            font-family: 'font2', sans-serif;
            font-size: 22px;
            color: black;
            margin: 0;
        }
        .order-detail-status {
            font-family: 'font1', sans-serif;
            font-size: 13px;
            padding: 4px 16px;
            border-radius: 20px;
            font-weight: 600;
            margin-left: auto;
            transition: all 0.3s ease;
        }
        .order-detail-status.pending { background: #fff3cd; color: #856404; }
        .order-detail-status.confirmed { background: #cce5ff; color: #004085; }
        .order-detail-status.shipped { background: #d4edda; color: #155724; }
        .order-detail-status.delivered { background: #28a745; color: white; }
        .order-detail-status.cancelled { background: #f8d7da; color: #721c24; }

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
            background: #f8f9fa;
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid #eee;
        }
        .order-info-grid .info-item {
            display: flex;
            flex-direction: column;
        }
        .order-info-grid .info-item label {
            font-family: 'font1', sans-serif;
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 600;
        }
        .order-info-grid .info-item span {
            font-family: 'font1', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #222;
            margin-top: 2px;
        }

        .order-items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .order-items-table th {
            background: #f8f9fa;
            font-family: 'font1', sans-serif;
            font-size: 12px;
            font-weight: 600;
            padding: 10px 14px;
            text-align: left;
            border-bottom: 2px solid #e9ecef;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .order-items-table th:last-child {
            text-align: right;
        }
        .order-items-table td {
            font-family: 'font1', sans-serif;
            font-size: 13px;
            padding: 10px 14px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        .order-items-table td:last-child {
            text-align: right;
        }
        .order-items-table td:nth-child(3),
        .order-items-table td:nth-child(4) {
            text-align: center;
        }
        .order-items-table .item-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }
        .order-items-table .item-name {
            font-weight: 500;
        }
        .order-items-table .item-name a {
            color: #222;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .order-items-table .item-name a:hover {
            color: #ff6ab5;
        }
        .order-items-table .item-total {
            font-weight: 600;
            color: #ff6ab5;
        }
        .order-total-row {
            background: #f8f9fa;
            font-weight: 600;
        }
        .order-total-row td {
            padding: 12px 14px;
            font-size: 15px;
        }
        .order-total-row .total-label {
            text-align: right;
            font-weight: 600;
            color: #333;
        }
        .order-total-row .total-value {
            color: #ff6ab5;
            font-size: 18px;
            font-weight: 700;
        }
        .order-total-row td:last-child {
            text-align: right;
        }

        .back-to-orders {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: white;
            border: 2px solid black;
            border-radius: 8px;
            color: black;
            text-decoration: none;
            font-family: 'font1', sans-serif;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        .back-to-orders:hover {
            background: #ff6ab5;
            transform: translateX(-4px);
            box-shadow: 4px 4px black;
        }

        .section-title {
            font-family: 'font2', sans-serif;
            font-size: 18px;
            margin-bottom: 12px;
            color: #222;
        }

        .price-nowrap {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .order-detail-header {
                flex-wrap: wrap;
                gap: 10px;
            }
            .order-detail-status {
                margin-left: 0;
                width: 100%;
                text-align: center;
            }
            .order-info-grid {
                grid-template-columns: 1fr 1fr;
                padding: 14px 16px;
                gap: 10px;
            }
            .order-info-grid .info-item span {
                font-size: 13px;
            }
            .order-items-table {
                font-size: 12px;
            }
            .order-items-table th,
            .order-items-table td {
                padding: 6px 10px;
                font-size: 12px;
            }
            .order-items-table .item-image {
                width: 35px;
                height: 35px;
            }
            .order-total-row td {
                font-size: 13px;
                padding: 8px 10px;
            }
            .order-total-row .total-value {
                font-size: 16px;
            }
            .back-to-orders span {
                display: none;
            }
            .order-detail-header h2 {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .order-info-grid {
                grid-template-columns: 1fr;
                padding: 12px 14px;
            }
            .order-info-grid .info-item span {
                font-size: 13px;
            }
            .order-items-table th,
            .order-items-table td {
                padding: 5px 8px;
                font-size: 11px;
            }
            .order-items-table .item-image {
                width: 30px;
                height: 30px;
            }
            .order-detail-header h2 {
                font-size: 17px;
            }
            .order-total-row .total-value {
                font-size: 15px;
            }
            .section-title {
                font-size: 16px;
            }
        }
    </style>
</head>
<body class="order-detail-page">

<?php include 'profile_header.php'; ?>

<div class="main-content">
    <div class="orders-container">
        <div class="order-detail-header">
            <a href="orders.php" class="back-to-orders">
                <i class="fas fa-arrow-left"></i>
                <span>Назад</span>
            </a>
            <h2>Заказ №<?= htmlspecialchars($order['order_number']) ?></h2>
            <span class="order-detail-status <?= $order['status'] ?>" id="orderStatus">
                <?= getStatusLabel($order['status']) ?>
            </span>
        </div>

        <div class="order-info-grid">
            <div class="info-item">
                <label>Дата заказа</label>
                <span><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></span>
            </div>
            <div class="info-item">
                <label>Телефон</label>
                <span><?= htmlspecialchars($order['phone'] ?? '-') ?></span>
            </div>
            <div class="info-item">
                <label>Адрес доставки</label>
                <span><?= htmlspecialchars($order['address'] ?? '-') ?></span>
            </div>
            <div class="info-item">
                <label>Статус</label>
                <span id="statusText"><?= getStatusLabel($order['status']) ?></span>
            </div>
        </div>

        <h3 class="section-title">Товары в заказе</h3>

        <table class="order-items-table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Название</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #999;">
                        Товары не найдены
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php if (!empty($item['image'])): ?>
                            <img src="/kickzone/uploads/products/<?= htmlspecialchars($item['image']) ?>" 
                                 class="item-image" alt="<?= htmlspecialchars($item['name']) ?>">
                        <?php else: ?>
                            <div class="item-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999; font-size: 10px; border-radius: 6px;">—</div>
                        <?php endif; ?>
                    </td>
                    <td class="item-name">
                        <a href="/kickzone/product-card.php?id=<?= $item['product_id'] ?>">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                    </td>
                    <td><?= $item['quantity'] ?> шт.</td>
                    <td class="price-nowrap"><?= number_format($item['price'], 0, '', ' ') ?> ₽</td>
                    <td class="item-total price-nowrap"><?= number_format($item['quantity'] * $item['price'], 0, '', ' ') ?> ₽</td>
                </tr>
                <?php endforeach; ?>
                <tr class="order-total-row">
                    <td colspan="4" class="total-label">Итого:</td>
                    <td class="total-value price-nowrap"><?= number_format($order['total'], 0, '', ' ') ?> ₽</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    let loadInterval = null;

    async function updateOrderStatus() {
        try {
            const orderId = <?= $order_id ?>;
            const response = await fetch('/kickzone/api/get_order_status.php?order_id=' + orderId);
            const data = await response.json();

            if (data.success) {
                const statusSpan = document.getElementById('orderStatus');
                const statusText = document.getElementById('statusText');

                const labels = {
                    'pending': 'Ожидает',
                    'confirmed': 'Подтверждён',
                    'shipped': 'Отправлен',
                    'delivered': 'Доставлен',
                    'cancelled': 'Отменён'
                };

                const label = labels[data.status] || data.status;

                if (statusSpan) {
                    statusSpan.className = 'order-detail-status ' + data.status;
                    statusSpan.textContent = label;
                }

                if (statusText) {
                    statusText.textContent = label;
                }
            }
        } catch (error) {
            console.error('Ошибка обновления статуса заказа:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateOrderStatus();
        loadInterval = setInterval(updateOrderStatus, 5000);
    });
</script>

</body>
</html>