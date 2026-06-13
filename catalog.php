<?php
 error_reporting(E_ALL);
ini_set('display_errors', 1);

$title = "Каталог";
$year = date("Y");
?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/catalog-main.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="script.js"></script>
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'catalog-main.php'; ?>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>
</body>
</html>