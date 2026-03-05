<?php

session_start();

// Если пользователь уже авторизован, перенаправляем на главную админ-страницу
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit();
}

$error_message = '';
if (isset($_GET['error']) && $_GET['error'] === '1') {
    $error_message = '<p style="color: red;">Неверный логин или пароль.</p>';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 80vh; background-color: #f4f4f4; }
        .login-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .login-container h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"],
        .form-group input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group input[type="checkbox"] { margin-right: 5px; }
        .form-group .remember-me { display: flex; align-items: center; font-size: 0.9em; }
        .form-group button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .form-group button:hover { background-color: #0056b3; }
        .error-message { text-align: center; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Вход в админ-панель</h2>
        <?php echo $error_message; ?>
        <form action="../process_login.php" method="post">
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" required minlength="5">
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <div class="remember-me">
                    <input type="checkbox" id="rememberme" name="rememberme">
                    <label for="rememberme">Запомнить меня</label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit">Войти</button>
            </div>
        </form>
    </div>

    <script>
        // JavaScript для интерактивности (необязательно, но рекомендуется)
        const loginInput = document.getElementById('login');
        const passwordInput = document.getElementById('password');

        loginInput.addEventListener('focus', function() {
            // Планируем удаление подсказки через некоторое время после того, как поле потеряет фокус
            // Для простоты, мы можем добавить подсказку и удалять при фокусе, но это не совсем как в примере.
            // В этом примере, подсказка будет удаляться при получении фокуса.
        });

        passwordInput.addEventListener('focus', function() {
            // Аналогично для поля пароля
        });
    </script>
</body>
</html>
