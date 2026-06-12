<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Редактировать товар' : 'Добавить товар' ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            padding: 12px 24px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .image-preview {
            margin-top: 10px;
            max-width: 200px;
        }
        .image-preview img {
            width: 100%;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1><?= $is_edit ? 'Редактировать товар' : '➕ Добавить товар' ?></h1>
            
            <form action="/kickzone/api/admin/update_product.php" method="POST" enctype="multipart/form-data">
                <?php if ($is_edit): ?>
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Название товара *</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($product['name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
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
                
                <div class="form-group">
                    <label>Slug (URL)</label>
                    <input type="text" name="slug" value="<?= htmlspecialchars($product['slug'] ?? '') ?>">
                    <small>Оставьте пустым для автоматической генерации</small>
                </div>
                
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Цена *</label>
                    <input type="number" step="0.01" name="price" required value="<?= $product['price'] ?? '' ?>">
                </div>
                
                <div class="form-group">
                    <label>Количество на складе</label>
                    <input type="number" name="stock" value="<?= $product['stock'] ?? 0 ?>">
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
                
                <button type="submit">Сохранить</button>
                <a href="products.php" style="margin-left: 10px;">Отмена</a>
            </form>
        </div>
    </div>
</body>
</html>