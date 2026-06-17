<?php 
$title = 'Товары';
include __DIR__ . '/../header.php'; 
?>

<div class="content-card">
    <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
        <h2 class="section-title">Товары</h2>
        <a href="product_form.php" class="btn-add">+</a>
    </div>
    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Бренд</th>
                    <th>Цвет</th>
                    <th>Размеры</th>
                    <th>Цена</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Нет товаров</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr style="height: 80px;">
                            <td style="vertical-align: middle;"><?= $product['id'] ?></td>
                            <td style="vertical-align: middle;">
                                <?php if ($product['image']): ?>
                                    <img src="../uploads/products/<?= htmlspecialchars($product['image']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                    <span style="display: inline-block; width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px;"></span>
                                <?php endif; ?>
                            </td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($product['name']) ?></td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?></td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($product['brand'] ?? '') ?></td>
                            <td style="vertical-align: middle; text-align: left;"><?= htmlspecialchars($product['color'] ?? '') ?></td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($product['sizes'] ?? '') ?></td>
                            <td style="vertical-align: middle;"><?= number_format($product['price'], 2) ?> ₽</td>
                            <td style="vertical-align: middle;">
                                <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                    <a href="product_form.php?id=<?= $product['id'] ?>" class="btn-icon btn-edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="products.php?delete_id=<?= $product['id'] ?>" class="btn-icon btn-delete" onclick="return confirm('Удалить товар?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>