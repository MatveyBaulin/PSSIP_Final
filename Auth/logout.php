<?php
// admin/logout.php
session_start();

// Уничтожаем все переменные сессии
$_SESSION = array();

// Если вы хотите удалить сессию полностью, также удалите cookie сессии.
// Примечание: это уничтожит сессию, а не только данные сессии!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Уничтожаем сессию
session_destroy();

// Удаляем cookie "Запомнить меня"
setcookie('admin_remember', '', time() - 3600, '/', $_SERVER['HTTP_HOST']);

// Перенаправляем на главную страницу
header('Location: ../index.php');
exit();
?>
