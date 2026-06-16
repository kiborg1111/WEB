<?php 
$title = $is_edit ? 'Редактировать товар' : 'Добавить товар';
include __DIR__ . '/../header.php'; 
?>

<div class="form-container">
    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px;">
        <a href="products.php" class="back-home-btn">
            <i class="fas fa-angle-left"></i>
        </a>
        <h2 class="section-title" style="margin: 0;"><?= $is_edit ? 'Редактировать товар' : 'Добавить товар' ?></h2>
    </div>
    
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
                <select name="brand_id">
                    <option value="">Выберите бренд</option>
                    <?php
                    $brands = $conn->query("SELECT id, name FROM brands ORDER BY name");
                    while ($brand = $brands->fetch_assoc()):
                    ?>
                        <option value="<?= $brand['id'] ?>" <?= isset($product) && $product['brand_id'] == $brand['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($brand['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group" style="flex: 1;">
                <label>Цвет</label>
                <select name="color_id">
                    <option value="">Выберите цвет</option>
                    <?php
                    $colors = $conn->query("SELECT id, name, value FROM colors ORDER BY name");
                    while ($color = $colors->fetch_assoc()):
                    ?>
                        <option value="<?= $color['id'] ?>" <?= isset($product) && $product['color_id'] == $color['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($color['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        
        <div class="row-2cols" style="display: flex; gap: 20px;">
            <div class="form-group" style="flex: 1;">
                <label>Размер</label>
                <select name="size_id">
                    <option value="">Выберите размер</option>
                    <?php
                    $sizes = $conn->query("SELECT id, value FROM sizes ORDER BY sort_order");
                    while ($size = $sizes->fetch_assoc()):
                    ?>
                        <option value="<?= $size['id'] ?>" <?= isset($product) && $product['size_id'] == $size['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($size['value']) ?>
                        </option>
                    <?php endwhile; ?>
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
                    <img src="../uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="Текущее изображение" style="max-width: 100px;">
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

<style>
.back-home-btn {
    background: #f0f0f0;
    color: black;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #ddd;
    transition: all 0.2s ease;
}

.back-home-btn:hover {
    background: #ff6ab5;
    color: black;
    transform: translateY(-2px);
    border-color: black;
    box-shadow: 4px 4px rgb(0, 0, 0);
}
</style>

</body>
</html>