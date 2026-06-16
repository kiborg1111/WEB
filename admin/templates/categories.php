<?php 
$title = 'Категории';
include __DIR__ . '/../header.php'; 
?>

<div class="content-card">
    <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
        <h2 class="section-title">Категории</h2>
        <a href="category_form.php" class="btn-add">+</a>
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
                        <tr style="height: 80px;">
                            <td style="vertical-align: middle;"><?= $cat['id'] ?></td>
                            <td style="vertical-align: middle;">
                                <?php if ($cat['image']): ?>
                                    <img src="../uploads/categories/<?= htmlspecialchars($cat['image']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                    <span style="display: inline-block; width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px;"></span>
                                <?php endif; ?>
                            </td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($cat['name']) ?></td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($cat['slug']) ?></td>
                            <td style="vertical-align: middle;"><?= htmlspecialchars($cat['description']) ?></td>
                            <td style="vertical-align: middle;"><?= $cat['sort_order'] ?></td>
                            <td style="vertical-align: middle;"><?= $count ?></td>
                            <td style="vertical-align: middle;">
                                <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                    <a href="category_form.php?id=<?= $cat['id'] ?>" class="btn-icon btn-edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="categories.php?delete_id=<?= $cat['id'] ?>" class="btn-icon btn-delete" onclick="return confirm('Удалить категорию?')">
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