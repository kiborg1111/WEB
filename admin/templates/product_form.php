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
        .row-2cols {
            display: flex;
            gap: 20px;
        }
        .row-2cols .form-group {
            flex: 1;
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
                
                <div class="row-2cols">
                    <div class="form-group">
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
                    
                    <div class="form-group">
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
                
                <div class="row-2cols">
                    <div class="form-group">
                        <label>Размер</label>
                        <select name="size">
                            <option value="">Выберите размер</option>
                            <?php for($s = 39; $s <= 45; $s++): ?>
                                <option value="<?= $s ?>" <?= isset($product) && $product['size'] == $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endfor; ?>
                        </select>
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
                
                <div class="row-2cols">
                    <div class="form-group">
                        <label>Цена *</label>
                        <input type="number" step="0.01" name="price" required value="<?= $product['price'] ?? '' ?>">
                    </div>
                    
                    <div class="form-group">
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
                
                <button type="submit">Сохранить</button>
                <a href="products.php" style="margin-left: 10px;">Отмена</a>
            </form>
        </div>
    </div>
</body>
</html>