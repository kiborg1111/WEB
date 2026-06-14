<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="/kickzone/style/header.css">
    <link rel="stylesheet" href="/kickzone/style/footer.css">
    <link rel="stylesheet" href="/kickzone/account_style/register.css">
</head>
<body>

<?php include '../header.php'; ?>

<div class="register-page">
    <h1>Создать аккаунт</h1>
    <div class="subtitle">Присоединяйтесь к KickZone</div>
    
    <div id="errorMessage" class="error-message"></div>
    <div id="successMessage" class="success-message"></div>
    
    <form id="registerForm">
        <div class="form-group">
            <label>Имя пользователя</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Подтвердите пароль</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn-register">Зарегистрироваться</button>
    </form>
    
    <div class="login-link">
        Уже есть аккаунт? <a href="login.php">Войти</a>
    </div>
</div>

<?php include '../footer.php'; ?>

<script>
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        const errorDiv = document.getElementById('errorMessage');
        const successDiv = document.getElementById('successMessage');
        
        // Скрываем предыдущие сообщения
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        
        // Проверка совпадения паролей
        if (password !== confirmPassword) {
            errorDiv.textContent = 'Пароли не совпадают';
            errorDiv.style.display = 'block';
            return;
        }
        
        // Проверка длины пароля
        if (password.length < 6) {
            errorDiv.textContent = 'Пароль должен быть не менее 6 символов';
            errorDiv.style.display = 'block';
            return;
        }
        
        try {
            const response = await fetch('/kickzone/api/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, email, password })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Успешная регистрация
                successDiv.textContent = data.message || 'Регистрация успешна! Перенаправление на вход...';
                successDiv.style.display = 'block';
                
                // Через 2 секунды перенаправляем на страницу входа
                setTimeout(() => {
                    window.location.href = '/kickzone/account/login.php';
                }, 2000);
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