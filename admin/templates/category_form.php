<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Редактировать категорию' : 'Добавить категорию' ?></title>
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
    <div class="form-container">
        <h2 class="section-title" style="margin-bottom: 30px;"><?= $is_edit ? 'Редактировать категорию' : '➕ Добавить категорию' ?></h2>
        
        <form action="update_category.php" method="POST" enctype="multipart/form-data">
            <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?= $category['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Название категории *</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($category['name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Slug (URL)</label>
                <input type="text" name="slug" value="<?= htmlspecialchars($category['slug'] ?? '') ?>">
                <small>Оставьте пустым для автоматической генерации</small>
            </div>
            
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Порядок сортировки</label>
                <input type="number" name="sort_order" value="<?= $category['sort_order'] ?? 0 ?>">
            </div>
            
            <div class="form-group">
                <label>Изображение категории</label>
                <input type="file" name="image" accept="image/*">
                <?php if ($is_edit && $category['image']): ?>
                    <div class="image-preview">
                        <img src="../uploads/categories/<?= htmlspecialchars($category['image']) ?>" alt="Текущее изображение">
                        <small>Текущее изображение</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit">Сохранить</button>
                <a href="categories.php" style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none;">Отмена</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>