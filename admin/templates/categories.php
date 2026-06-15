<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление категориями</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="admin-header">
    <h1>Админ-панель</h1>
    <a href="/kickzone/account/logout.php" class="logout-btn">Выход</a>
</div>

<div class="nav-links">
    <a href="index.php">Заказы</a>
    <a href="products.php">Товары</a>
    <a href="categories.php" class="active">Категории</a>
</div>

<div class="container">
    <div class="content-card">
        <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <h2 class="section-title">Категории</h2>
            <a href="category_form.php" class="btn-add">+ Добавить категорию</a>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div style="margin: 0 20px 20px 20px;">
                <div class="error"><?= $_SESSION['error'] ?></div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="table-wrapper">
            <table class="admin-table">
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
                            <td colspan="8" style="text-align: center;">Нет категорий</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
                            $stmt->bind_param("i", $cat['id']);
                            $stmt->execute();
                            $count = $stmt->get_result()->fetch_assoc()['count'];
                            ?>
                            <tr>
                                <td><?= $cat['id'] ?></td>
                                <td>
                                    <?php if ($cat['image']): ?>
                                        <img src="../uploads/categories/<?= htmlspecialchars($cat['image']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
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
                                    <a href="category_form.php?id=<?= $cat['id'] ?>" class="btn-icon btn-edit">✏️</a>
                                    <a href="categories.php?delete_id=<?= $cat['id'] ?>" class="btn-icon btn-delete" onclick="return confirm('Удалить категорию? Товары не удалятся, останутся без категории.')">🗑️</a>
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