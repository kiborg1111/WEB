<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /kickzone/account/login.php');
    exit;
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$order_id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    $allowed = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
    if (in_array($new_status, $allowed)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        header("Location: order_details.php?id=" . $order_id);
        exit;
    }
}

$stmt = $conn->prepare("
    SELECT o.*, u.username, u.email, u.full_name
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("
    SELECT oi.*, p.image 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$title = 'Детали заказа';
include __DIR__ . '/header.php';
?>

<style>
.status-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-left: 10px;
    vertical-align: middle;
}
.status-dot-pending { background-color: #ffc107; box-shadow: 0 0 4px #ffc107; }
.status-dot-confirmed { background-color: #0d6efd; box-shadow: 0 0 4px #0d6efd; }
.status-dot-shipped { background-color: #198754; box-shadow: 0 0 4px #198754; }
.status-dot-delivered { background-color: #ff6ab5; box-shadow: 0 0 4px #ff6ab5; }
.status-dot-cancelled { background-color: #dc3545; box-shadow: 0 0 4px #dc3545; }
</style>

<div class="content-card">
    <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 20px;">
        <a href="index.php" class="back-home-btn">
            <i class="fas fa-angle-left"></i>
        </a>
        <h2 class="section-title" style="margin: 0;">Заказ №<?= htmlspecialchars($order['order_number']) ?></h2>
    </div>
    
    <div style="padding: 0 20px 20px 20px;">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 20px;">
            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px;">
                <strong>Пользователь:</strong> 
                <?php if ($order['full_name']): ?>
                    <?= htmlspecialchars($order['full_name']) ?>
                    <span style="color: #333; font-size: 14px; font-weight: normal;">(<?= htmlspecialchars($order['username']) ?>)</span>
                <?php else: ?>
                    <?= htmlspecialchars($order['username']) ?>
                <?php endif; ?>
            </div>
            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px;">
                <strong>Email:</strong> <?= htmlspecialchars($order['email']) ?>
            </div>
            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px;">
                <strong>Адрес:</strong> <?= htmlspecialchars($order['address']) ?>
            </div>
            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px;">
                <strong>Телефон:</strong> <?= htmlspecialchars($order['phone']) ?>
            </div>
            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px;">
                <strong>Дата:</strong> <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
            </div>
            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <strong>Статус:</strong>
                <span class="status-dot status-dot-<?= $order['status'] ?>"></span>
                <form method="POST" style="display: inline-block;">
                    <select name="status" onchange="this.form.submit()" style="padding: 4px 8px; border-radius: 6px; border: 1px solid #ddd;">
                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Ожидает</option>
                        <option value="confirmed" <?= $order['status'] == 'confirmed' ? 'selected' : '' ?>>Подтверждён</option>
                        <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Отправлен</option>
                        <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Доставлен</option>
                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Отменён</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
    
    <div style="padding: 0 20px 20px 20px;">
        <h3 style="font-family: 'font2', sans-serif; margin-bottom: 20px;">Товары в заказе</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                        <th style="padding: 12px; text-align: left;">Изображение</th>
                        <th style="padding: 12px; text-align: left;">Товар</th>
                        <th style="padding: 12px; text-align: center;">Количество</th>
                        <th style="padding: 12px; text-align: right;">Цена</th>
                        <th style="padding: 12px; text-align: right;">Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr style="border-bottom: 1px solid #e9ecef;">
                            <td style="padding: 12px;">
                                <?php if ($item['image']): ?>
                                    <img src="../uploads/products/<?= htmlspecialchars($item['image']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #999;">—</div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($item['name']) ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <span style="display: inline-block; padding: 4px 10px; background: #f0f0f0; border-radius: 20px;">
                                    <?= $item['quantity'] ?> шт.
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: right;"><?= number_format($item['price'], 2) ?> ₽</td>
                            <td style="padding: 12px; text-align: right; font-weight: bold;"><?= number_format($item['quantity'] * $item['price'], 2) ?> ₽</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background: #f8f9fa;">
                        <td colspan="4" style="padding: 15px; text-align: right; font-weight: bold; font-size: 16px;">Итого:</td>
                        <td style="padding: 15px; text-align: right; font-weight: bold; font-size: 20px; color: #ff6ab5;"><?= number_format($order['total'], 2) ?> ₽</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.querySelector('select[name="status"]');
    const dot = document.querySelector('.status-dot');
    
    if (select && dot) {
        select.addEventListener('change', function() {
            const newStatus = this.value;
            
            fetch('../api/admin/update_order_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    order_id: <?= $order_id ?>, 
                    status: newStatus 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    dot.className = 'status-dot status-dot-' + newStatus;
                } else {
                    alert('Ошибка: ' + data.message);
                }
            });
        });
    }
});
</script>

</body>
</html>