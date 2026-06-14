<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в личный кабинет</title>
    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/footer.css">
    <link rel="stylesheet" href="../account_style/login.css">
</head>
<body>

<?php include '../header.php'; ?>

<div class="login-page">
    <h1>Добро пожаловать</h1>
    <div class="subtitle">Войдите в свой аккаунт KickZone</div>
    
    <div id="errorMessage" class="error-message"></div>
    
    <form id="loginForm">
        <div class="form-group">
            <label>Логин или Email</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-login">Войти</button>
    </form>
    
    <div class="register-link">
        Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
    </div>
</div>

<?php include '../footer.php'; ?>

<script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const errorDiv = document.getElementById('errorMessage');
        
        // Скрываем предыдущее сообщение об ошибке
        errorDiv.style.display = 'none';
        
        try {
            const response = await fetch('/kickzone/api/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Успешный вход
                window.location.href = '/kickzone/account/index.php';
            } else {
                // Показываем ошибку
                errorDiv.textContent = data.message;
                errorDiv.style.display = 'block';
            }
        } catch (error) {
            errorDiv.textContent = 'Ошибка соединения с сервером';
            errorDiv.style.display = 'block';
        }
    });
</script>

</body>
</html>