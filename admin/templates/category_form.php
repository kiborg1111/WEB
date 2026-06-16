<?php 
$title = $is_edit ? 'Редактировать категорию' : 'Добавить категорию';
include __DIR__ . '/../header.php'; 
?>

<div class="form-container">
    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px;">
        <a href="categories.php" class="back-home-btn">
            <i class="fas fa-angle-left"></i>
        </a>
        <h2 class="section-title" style="margin: 0;"><?= $is_edit ? 'Редактировать категорию' : 'Добавить категорию' ?></h2>
    </div>
    
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
        
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit">Сохранить</button>
            <a href="categories.php" style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none;">Отмена</a>
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