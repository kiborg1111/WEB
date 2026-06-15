<?php
session_start();
session_destroy();
header('Location: /kickzone/index.php');
exit;
?>