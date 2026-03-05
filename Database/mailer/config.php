<?php
// config.php - Настройки почты

// Настройки SMTP
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'baulinm2006@gmail.com'); // Ваш email
define('SMTP_PASS', '');    // Пароль приложения

// Email для получения писем
define('ADMIN_EMAIL', 'baulinm2006@mysite.com');
define('SITE_NAME', 'Мой сайт');

// Включение/отключение режима отладки
define('DEBUG_MODE', true);
?>