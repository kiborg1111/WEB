<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /kickzone/account/login.php');
    exit();
}

require_once '../db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Получаем данные пользователя
$stmt = $conn->prepare("SELECT username, email, full_name, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $full_name, $phone, $address, $user_id);
    
    if ($stmt->execute()) {
        $success = '✅ Данные успешно обновлены!';
        $user['full_name'] = $full_name;
        $user['phone'] = $phone;
        $user['address'] = $address;
    } else {
        $error = '❌ Ошибка при обновлении';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой профиль - KickZone</title>
    <link rel="stylesheet" href="/kickzone/style/header.css">
    <link rel="stylesheet" href="/kickzone/style/footer.css">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border: 2px solid black;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h1 {
            font-family: 'font2', sans-serif;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }
        .info-block {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .info-block p {
            margin: 8px 0;
            font-family: 'font1', sans-serif;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-family: 'font1', sans-serif;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            font-family: 'font1', sans-serif;
        }
        input:focus, textarea:focus {
            border-color: #ff6ab5;
            outline: none;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        button {
            width: 100%;
            padding: 14px;
            background: #ff6ab5;
            border: 2px solid black;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: scale(1.02);
        }
        .success {
            background: #e0ffe0;
            color: green;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .error {
            background: #ffe0e0;
            color: red;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #ff6ab5;
            text-decoration: none;
            font-family: 'font1', sans-serif;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/kickzone/header.php'; ?>

<div class="profile-container">
    <h1>👤 Мой профиль</h1>
    
    <!-- Неизменяемая информация -->
    <div class="info-block">
        <p><strong>📝 Имя пользователя:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>📧 Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    </div>
    
    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Форма для редактирования -->
    <form method="POST">
        <div class="form-group">
            <label>👤 Полное имя</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" placeholder="Введите ваше полное имя">
        </div>
        
        <div class="form-group">
            <label>📞 Телефон</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+7 (999) 123-45-67">
        </div>
        
        <div class="form-group">
            <label>🏠 Адрес доставки</label>
            <textarea name="address" placeholder="Ваш адрес для доставки"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </div>
        
        <button type="submit">💾 Сохранить изменения</button>
    </form>
    
    <hr>
    
    <a href="index.php" class="back-link">← Вернуться в личный кабинет</a>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/kickzone/footer.php'; ?>

</body>
</html>