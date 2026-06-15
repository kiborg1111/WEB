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
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['order_number']) ?></td>
                            <td><?= htmlspecialchars($order['username']) ?></td>
                            <td><?= number_format($order['total'], 2) ?> ₽</td>
                            <td><?= htmlspecialchars($order['address']) ?></td>
                            <td>
                                <select class="status-select" data-order-id="<?= $order['id'] ?>">
                                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Ожидает</option>
                                    <option value="confirmed" <?= $order['status'] == 'confirmed' ? 'selected' : '' ?>>Подтверждён</option>
                                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Отправлен</option>
                                    <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Доставлен</option>
                                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Отменён</option>
                                </select>
                            </td>
                            <td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td>
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