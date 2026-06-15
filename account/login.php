<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - KickZone</title>
    <link rel="stylesheet" href="/kickzone/style/header.css">
    <link rel="stylesheet" href="/kickzone/style/footer.css">
    <link rel="stylesheet" href="/kickzone/account_style/login.css">
</head>
<body>

<?php include '../header.php'; ?>

<div class="login-page">
    <div class="auth-container">
        <h1>Вход в аккаунт</h1>
        <div id="errorMessage" class="error"></div>
        
        <form id="loginForm">
            <div class="form-group">
                <label>Логин или Email</label>
                <input type="text" id="username" placeholder="Введите логин или email" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" id="password" placeholder="Введите пароль" required>
            </div>
            <button type="submit" class="btn-login">Войти</button>
        </form>
        
        <div class="link">
            Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>

<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorDiv = document.getElementById('errorMessage');
    
    errorDiv.style.display = 'none';
    
    try {
        const response = await fetch('/kickzone/api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = '/kickzone/account/profile.php';
        } else {
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