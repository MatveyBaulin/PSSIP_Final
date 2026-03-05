<?php

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: blue; }
        nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Панель управления</h1>

    <nav>
        <a href="index.php">Главная</a>
        <a href="dashboard.php">Панель управления</a>
        <a href="logout.php">Выйти</a>
    </nav>

    <p>Это страница вашей панели управления. Здесь могут быть различные виджеты и статистика.</p>
</body>
</html>
