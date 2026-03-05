<?php

session_start();

// Подключаем настройки
require_once 'config.php';

// Проверяем, был ли отправлен запрос методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    $rememberme = isset($_POST['rememberme']) ? true : false;

    // Проверяем введенные данные с данными из config.php
    if ($login === ADMIN_LOGIN && $password === ADMIN_PASSWORD) {
        // Авторизация успешна
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login'] = $login; // Можно сохранить логин для отображения

        // Если выбрано "Запомнить меня", можно установить cookie
        if ($rememberme) {
            // Важно: Для реальных приложений используйте более безопасные методы хранения сессий и шифрования
            setcookie('admin_remember', time() + (86400 * 30), '/', $_SERVER['HTTP_HOST']); // Cookie на 30 дней
        }

        // Перенаправляем на главную страницу админ-панели
        header('Location: Auth/index.php');
        exit();
    } else {
        // Неверный логин или пароль
        // Перенаправляем обратно на страницу входа с параметром ошибки
        header('Location: Auth/login.php?error=1');
        exit();
    }
} else {
    // Если запрос не POST, перенаправляем на главную страницу
    header('Location: index.php');
    exit();
}
?>
