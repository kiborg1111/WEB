<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление товарами</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .nav-links {
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
        }
        .nav-links a {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .nav-links a:hover {
            background: #0056b3;
        }
        .btn-add {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .btn-add:hover {
            background: #218838;
        }
        .actions a {
            margin: 0 5px;
            text-decoration: none;
            font-size: 18px;
        }
        .edit { color: #007bff; }
        .delete { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-links">
            <a href="index.php">Заказы</a>
            <a href="products.php" style="background: #28a745;">Товары</a>
        </div>
        
        <h1>Управление товарами</h1>
        
        <a href="product_form.php" class="btn-add">+ Добавить товар</a>
        
        <div class="orders-table">
            <table>
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
                            <td colspan="7">Нет товаров</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td>
                                <?php if ($product['image']): ?>
                                    <img src="../uploads/products/<?= htmlspecialchars($product['image']) ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?></td>
                            <td><?= number_format($product['price'], 2) ?> ₽</td>
                            <td><?= $product['stock'] ?></td>
                            <td class="actions">
                                <a href="product_form.php?id=<?= $product['id'] ?>" class="edit">✏️</a>
                                <a href="products.php?delete_id=<?= $product['id'] ?>" class="delete" onclick="return confirm('Удалить товар?')">🗑️</a>
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