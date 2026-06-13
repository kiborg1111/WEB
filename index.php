<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$title = "KickZone";
$year = date("Y");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/index-main.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<?php include 'index-main.php'; ?>

<?php include 'footer.php'; ?>

<script>
    // Ждём полной загрузки страницы
    document.addEventListener('DOMContentLoaded', function() {
        // Прямой запрос к API
        fetch('http://localhost/kickzone/api/products.php')
            .then(response => response.json())
            .then(data => {
                console.log('Товары получены:', data);
                
                if (data.success && data.products.length > 0) {
                    const container = document.getElementById('new-products-container');
                    if (container) {
                        const newProducts = data.products.slice(0, 4);
                        container.innerHTML = newProducts.map(product => `
                            <div class="card">
                                <img src="/uploads/products/${product.image || 'placeholder.jpg'}" style="width: 100%; height: 100%; object-fit: cover;">
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.6); color: white; padding: 10px; text-align: center;">
                                    <strong>${product.name}</strong><br>
                                    ${Number(product.price).toLocaleString()} ₽
                                </div>
                            </div>
                        `).join('');
                    }
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
    });
</script>

</body>
</html>