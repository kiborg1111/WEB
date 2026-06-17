<?php
session_start();
require_once 'includes/db.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    header('Location: /kickzone/catalog.php');
    exit();
}

$stmt = $conn->prepare("
    SELECT p.*, c.name as category_name, cl.name as color_name, cl.value as color_value
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    LEFT JOIN colors cl ON p.color_id = cl.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header('Location: /kickzone/catalog.php');
    exit();
}

$stmt = $conn->prepare("
    SELECT * FROM products 
    WHERE category_id = ? AND id != ? 
    LIMIT 4
");
$stmt->bind_param("ii", $product['category_id'], $product_id);
$stmt->execute();
$similar_products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - KickZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/kickzone/style/header.css">
    <link rel="stylesheet" href="/kickzone/style/footer.css">
    <link rel="stylesheet" href="/kickzone/style/product-card.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="product-page">
    <div class="breadcrumb">
        <a href="#" onclick="history.back(); return false;" class="back-home-btn">
            <i class="fas fa-angle-left"></i>
        </a>
    </div>

    <!-- Основная карточка товара -->
    <div class="product-card">
        <!-- Фото -->
        <div class="product-image">
            <img src="/kickzone/uploads/products/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>

        <!-- Информация -->
        <div class="product-info">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</div>
            
            <!-- Размер -->
           <div class="spec-item">
                <span class="spec-label">Размер</span>
                <span class="spec-value"><?= htmlspecialchars($product['size_value'] ?? 'Не указан') ?></span>
            </div>
            
            <!-- Цвет с кружком -->
            <div class="spec-item">
                <span class="spec-label">Цвет</span>
                <span class="spec-value">
                    <span class="color-circle" style="background-color: <?= htmlspecialchars($product['color_value'] ?? '#ccc') ?>;"></span>
                </span>
            </div>

            <!-- Кнопка Купить -->
            <button class="btn-buy" id="buyNowBtn">Купить</button>

            <!-- Характеристики (обычный текст) -->
            <div class="product-text">
                <p><?= nl2br(htmlspecialchars($product['description'] ?? 'Описание отсутствует')) ?></p>
            </div>
        </div>
    </div>

    <!-- Похожие товары -->
    <?php if (!empty($similar_products)): ?>
    <div class="similar-products">
        <h2>Похожие товары</h2>
        <div class="similar-grid">
            <?php foreach ($similar_products as $similar): ?>
                <a href="product-card.php?id=<?= $similar['id'] ?>" class="similar-item">
                    <img src="/kickzone/uploads/products/<?= htmlspecialchars($similar['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($similar['name']) ?>">
                    <h3><?= htmlspecialchars($similar['name']) ?></h3>
                    <div class="similar-price"><?= number_format($similar['price'], 0, '', ' ') ?> ₽</div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script src="/kickzone/script.js"></script>
<script>
    document.getElementById('buyNowBtn').addEventListener('click', async function() {
        const productId = <?= $product['id'] ?>;
        
        try {
            const response = await fetch('/kickzone/api/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    product_id: productId, 
                    quantity: 1
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = '/kickzone/account/cart.php';
            } else {
                alert('❌ ' + data.message);
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('❌ Ошибка при добавлении в корзину');
        }
    });
</script>

</body>
</html>