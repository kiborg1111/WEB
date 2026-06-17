<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О нас</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/kickzone/style/header.css">
    <link rel="stylesheet" href="/kickzone/style/about.css">
    <link rel="stylesheet" href="/kickzone/style/footer.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="map-container">
    <img src="/kickzone/photo/map.png" alt="Карта" class="map-bg">
    <img src="/kickzone/photo/skate.png" alt="скейт" class="skate-overlay">
</div>
<div class="divider"></div>
<div class="about-text">
    <div class="container">
        <h2>О нас</h2>
        <p>
            <span class="highlight">KickZone</span> — это интернет-магазин кроссовок и спортивной обуви. 
            Мы предлагаем только оригинальную продукцию от ведущих мировых брендов.
        </p>
        <p>
            Наша миссия — сделать качественную и стильную обувь доступной для каждого. 
            Мы тщательно отбираем ассортимент и гарантируем подлинность каждой пары.
        </p>
        <p>
            <span class="highlight">Почему мы?</span><br>
            100% оригинальная продукция<br>
            Бесплатная доставка от 5000 ₽<br>
            Возврат товара в течение 14 дней<br>
            Поддержка 24/7
        </p>
        <p style="margin-top: 20px;">
            <strong>Контакты:</strong><br>
            ул. Калинина, 8, Владивосток<br>
            +7 (999) 999-99-99<br>
            info@kickzone.ru<br>
            ПН-ЧТ 10:00-22:00
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>