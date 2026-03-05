<?php

session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Если нет, перенаправляем на страницу входа
    header('Location: login.php');
    exit();
}

// Проверяем, есть ли cookie "Запомнить меня"
if (isset($_COOKIE['admin_remember']) && !isset($_SESSION['admin_logged_in'])) {
    // Здесь можно было бы проверить валидность cookie и восстановить сессию,
    // но для простоты, мы предполагаем, что пользователь уже авторизован через сессию.
    // В более сложной системе, вы бы проверяли, истек ли срок cookie и т.д.
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: blue; }
        nav a:hover { text-decoration: underline; }
        .welcome { font-size: 1.2em; }
    </style>
</head>
<body>
    <h1>Панель администратора</h1>

    <nav>
        <a href="index.php">Главная</a>
        <a href="dashboard.php">Панель управления</a>
        <a href="logout.php">Выйти</a>
    </nav>

    <div class="welcome">
        Добро пожаловать, <?php echo htmlspecialchars($_SESSION['admin_login'] ?? 'Администратор'); ?>!
    </div>

    <p>Это главная страница вашей административной панели.</p>
    <p>Здесь вы можете управлять сайтом.</p>
</body>
</html>
