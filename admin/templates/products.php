<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление товарами</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="admin-header">
    <h1>Админ-панель</h1>
    <a href="/kickzone/account/logout.php" class="logout-btn">Выход</a>
</div>

<div class="nav-links">
    <a href="index.php">Заказы</a>
    <a href="products.php" class="active">Товары</a>
    <a href="categories.php">Категории</a>
</div>

<div class="container">
    <div class="content-card">
        <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <h2 class="section-title">Товары</h2>
            <a href="product_form.php" class="btn-add">+ Добавить товар</a>
        </div>
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Изображение</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Остаток</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">Нет товаров</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td>
                                <?php if ($product['image']): ?>
                                    <img src="../uploads/products/<?= htmlspecialchars($product['image']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?></td>
                            <td><?= number_format($product['price'], 2) ?> ₽</td>
                            <td><?= $product['stock'] ?></td>
                            <td class="actions">
                                <a href="product_form.php?id=<?= $product['id'] ?>" class="btn-icon btn-edit">✏️</a>
                                <a href="products.php?delete_id=<?= $product['id'] ?>" class="btn-icon btn-delete" onclick="return confirm('Удалить товар?')">🗑️</a>
                             </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>