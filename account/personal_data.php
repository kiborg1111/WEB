<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /kickzone/account/login.php');
    exit();
}

require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Получаем данные пользователя
$stmt = $conn->prepare("SELECT email, full_name, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личные данные - KickZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/kickzone/account_style/profile.css">
    <link rel="stylesheet" href="/kickzone/account_style/profile_header.css">
    <link rel="stylesheet" href="/kickzone/account_style/personal_data.css">
    <script src="/kickzone/script.js"></script>
</head>
<body>

<a href="/kickzone/index.php" class="back-home-btn">
    <i class="fas fa-angle-left" aria-label="назад"></i>
</a>

<?php include 'profile_header.php'; ?>

<div class="main-content">
    <div class="personal-container">
        <div class="personal-card">
            <h2>Личные данные</h2>
            
            <div class="messages-container">
                <div id="successMessage" class="success-message" style="display: none;"></div>
                <div id="errorMessage" class="error-message" style="display: none;"></div>
            </div>
            
            <div class="info-field">
                <label>Email</label>
                <div class="info-value" id="display_email"><?= htmlspecialchars($user['email']) ?></div>
            </div>
            
            <form id="profileForm">
                <div class="form-group">
                    <label>Полное имя</label>
                    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" placeholder="Введите ваше полное имя">
                </div>
                
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+7 (999) 123-45-67">
                </div>
                
                <div class="form-group">
                    <label>Адрес доставки</label>
                    <textarea id="address" name="address" placeholder="Ваш адрес для доставки"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>
                
                <div class="password-section">
                    <h3>Смена пароля</h3>
                    <div class="form-group">
                        <label>Старый пароль</label>
                        <input type="password" id="old_password" placeholder="Введите старый пароль">
                    </div>
                    <div class="form-group">
                        <label>Новый пароль</label>
                        <input type="password" id="new_password" placeholder="Введите новый пароль (мин. 6 символов)">
                    </div>
                    <div class="form-group">
                        <label>Подтвердите пароль</label>
                        <input type="password" id="confirm_password" placeholder="Повторите новый пароль">
                    </div>
                </div>
                
                <button type="submit" class="btn-save">Сохранить изменения</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('profileForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Убрал username, оставил только email
        const email = document.getElementById('display_email').innerText;
        const full_name = document.getElementById('full_name').value;
        const phone = document.getElementById('phone').value;
        const address = document.getElementById('address').value;
        const old_password = document.getElementById('old_password').value;
        const new_password = document.getElementById('new_password').value;
        const confirm_password = document.getElementById('confirm_password').value;
        
        const successDiv = document.getElementById('successMessage');
        const errorDiv = document.getElementById('errorMessage');
        
        successDiv.style.display = 'none';
        errorDiv.style.display = 'none';
        
        // Смена пароля
        if (old_password || new_password || confirm_password) {
            if (new_password !== confirm_password) {
                errorDiv.textContent = 'Новый пароль и подтверждение не совпадают';
                errorDiv.style.display = 'block';
                return;
            }
            
            if (new_password.length < 6) {
                errorDiv.textContent = 'Новый пароль должен быть не менее 6 символов';
                errorDiv.style.display = 'block';
                return;
            }
            
            try {
                const result = await changePassword(old_password, new_password);
                if (result.success) {
                    successDiv.textContent = result.message;
                    successDiv.style.display = 'block';
                    document.getElementById('old_password').value = '';
                    document.getElementById('new_password').value = '';
                    document.getElementById('confirm_password').value = '';
                } else {
                    errorDiv.textContent = result.message;
                    errorDiv.style.display = 'block';
                }
            } catch (error) {
                errorDiv.textContent = 'Ошибка соединения с сервером';
                errorDiv.style.display = 'block';
            }
        }
        
        // Обновление профиля (без username)
        try {
            const result = await updateProfile(email, full_name, phone, address);
            if (result.success) {
                successDiv.textContent = result.message;
                successDiv.style.display = 'block';
            } else {
                errorDiv.textContent = result.message;
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