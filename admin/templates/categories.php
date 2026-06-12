<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление категориями</title>
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
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-links">
            <a href="index.php">Заказы</a>
            <a href="products.php">Товары</a>
            <a href="categories.php" style="background: #28a745;">Категории</a>
        </div>
        
        <h1>📂 Управление категориями</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <a href="category_form.php" class="btn-add">+ Добавить категорию</a>
        
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Изображение</th>
                        <th>Название</th>
                        <th>Slug</th>
                        <th>Описание</th>
                        <th>Порядок</th>
                        <th>Товаров</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="8">Нет категорий</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <?php
                            // Считаем товары в категории
                            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
                            $stmt->bind_param("i", $cat['id']);
                            $stmt->execute();
                            $count = $stmt->get_result()->fetch_assoc()['count'];
                            ?>
                            <tr>
                                <td><?= $cat['id'] ?></td>
                                <td>
                                    <?php if ($cat['image']): ?>
                                        <img src="../uploads/categories/<?= htmlspecialchars($cat['image']) ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($cat['name']) ?></td>
                                <td><?= htmlspecialchars($cat['slug']) ?></td>
                                <td><?= htmlspecialchars($cat['description']) ?></td>
                                <td><?= $cat['sort_order'] ?></td>
                                <td><?= $count ?></td>
                                <td class="actions">
                                    <a href="category_form.php?id=<?= $cat['id'] ?>" class="edit">✏️</a>
                                    <a href="categories.php?delete_id=<?= $cat['id'] ?>" class="delete" onclick="return confirm('Удалить категорию? Товары не удалятся, останутся без категории.')">🗑️</a>
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