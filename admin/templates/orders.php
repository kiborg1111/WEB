<?php 
$title = 'Заказы';
include __DIR__ . '/../header.php'; 
?>

<div class="content-card">
    <div style="padding: 20px; border-bottom: 1px solid #eee;">
        <h2 class="section-title">Все заказы</h2>
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
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Заказов пока нет</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr style="height: 80px;">
                            <td style="vertical-align: middle;">#<?= $order['id'] ?></td>
                            <td style="vertical-align: middle;">
                                <a href="order_details.php?id=<?= $order['id'] ?>" style="color: #007bff; text-decoration: none;">
                                    <?= htmlspecialchars($order['order_number']) ?>
                                </a>
                            </td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($order['username']) ?></td>
                            <td style="vertical-align: middle;"><?= number_format($order['total'], 2) ?> ₽</td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($order['address']) ?></td>
                            <td style="vertical-align: middle;">
                                <select class="status-select" data-order-id="<?= $order['id'] ?>">
                                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Ожидает</option>
                                    <option value="confirmed" <?= $order['status'] == 'confirmed' ? 'selected' : '' ?>>Подтверждён</option>
                                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Отправлен</option>
                                    <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Доставлен</option>
                                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Отменён</option>
                                </select>
                            </td>
                            <td style="vertical-align: middle;">
                                <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                                <span class="status-dot status-dot-<?= $order['status'] ?>"></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="assets/admin.js"></script>
</body>
</html>