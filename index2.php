<?php

session_start(); // Запускаем сессию, чтобы проверить, авторизован ли пользователь

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница сайта</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        a { text-decoration: none; color: blue; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Добро пожаловать на наш сайт!</h1>
    <p>Это главная страница. Вы можете ознакомиться с нашим контентом.</p>

    <p><a href="/Auth/login.php">Войти в админ-панель</a></p>

    <?php
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        echo '<p>Вы уже вошли в админ-панель.</p>';
        echo '<p><a href="/Auth/index.php">Перейти в админ-панель</a></p>';
        echo '<p><a href="/Auth/logout.php">Выйти из админ-панели</a></p>';
    }
    ?>
</body>
</html>
