<?php
 error_reporting(E_ALL);
ini_set('display_errors', 1);

$title = "kickzone";
$year = date("Y");
?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="fonts/font1.ttf">
    <link rel="stylesheet" href="fonts/font2.ttf">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<?php include 'main.php'; ?>

</body>
</html>