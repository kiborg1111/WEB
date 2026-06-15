<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Редактировать товар' : 'Добавить товар' ?></title>
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
    <div class="form-container">
        <h2 class="section-title" style="margin-bottom: 30px;"><?= $is_edit ? 'Редактировать товар' : '➕ Добавить товар' ?></h2>
        
        <form action="/kickzone/api/admin/update_product.php" method="POST" enctype="multipart/form-data">
            <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Название товара *</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($product['name'] ?? '') ?>">
            </div>
            
            <div class="row-2cols" style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Бренд</label>
                    <select name="brand">
                        <option value="">Выберите бренд</option>
                        <option value="Nike" <?= isset($product) && $product['brand'] == 'Nike' ? 'selected' : '' ?>>Nike</option>
                        <option value="Adidas" <?= isset($product) && $product['brand'] == 'Adidas' ? 'selected' : '' ?>>Adidas</option>
                        <option value="Puma" <?= isset($product) && $product['brand'] == 'Puma' ? 'selected' : '' ?>>Puma</option>
                        <option value="New Balance" <?= isset($product) && $product['brand'] == 'New Balance' ? 'selected' : '' ?>>New Balance</option>
                        <option value="Asics" <?= isset($product) && $product['brand'] == 'Asics' ? 'selected' : '' ?>>Asics</option>
                        <option value="Reebok" <?= isset($product) && $product['brand'] == 'Reebok' ? 'selected' : '' ?>>Reebok</option>
                    </select>
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label>Цвет</label>
                    <select name="color">
                        <option value="">Выберите цвет</option>
                        <option value="black" <?= isset($product) && $product['color'] == 'black' ? 'selected' : '' ?>>Черный</option>
                        <option value="white" <?= isset($product) && $product['color'] == 'white' ? 'selected' : '' ?>>Белый</option>
                        <option value="red" <?= isset($product) && $product['color'] == 'red' ? 'selected' : '' ?>>Красный</option>
                        <option value="blue" <?= isset($product) && $product['color'] == 'blue' ? 'selected' : '' ?>>Синий</option>
                        <option value="grey" <?= isset($product) && $product['color'] == 'grey' ? 'selected' : '' ?>>Серый</option>
                        <option value="green" <?= isset($product) && $product['color'] == 'green' ? 'selected' : '' ?>>Зеленый</option>
                    </select>
                </div>
            </div>
            
            <div class="row-2cols" style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Размер</label>
                    <select name="size">
                        <option value="">Выберите размер</option>
                        <?php for($s = 39; $s <= 45; $s++): ?>
                            <option value="<?= $s ?>" <?= isset($product) && $product['size'] == $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label>Категория *</label>
                    <select name="category_id" required>
                        <option value="">Выберите категорию</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= isset($product) && $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Slug (URL)</label>
                <input type="text" name="slug" value="<?= htmlspecialchars($product['slug'] ?? '') ?>">
                <small>Оставьте пустым для автоматической генерации</small>
            </div>
            
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
            </div>
            
            <div class="row-2cols" style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Цена *</label>
                    <input type="number" step="0.01" name="price" required value="<?= $product['price'] ?? '' ?>">
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label>Количество на складе</label>
                    <input type="number" name="stock" value="<?= $product['stock'] ?? 0 ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label>Изображение</label>
                <input type="file" name="image" accept="image/*">
                <?php if ($is_edit && $product['image']): ?>
                    <div class="image-preview">
                        <img src="../uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="Текущее изображение">
                        <small>Текущее изображение</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit">Сохранить</button>
                <a href="products.php" style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none;">Отмена</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>