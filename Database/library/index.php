<?php
session_start();

// Автозагрузка классов
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Загрузка конфигурации
require_once __DIR__ . '/config/database.php';

use App\Core\Router;

// Создаем роутер
$router = new Router();

// Определяем маршруты
$router->add('/', 'BookController', 'index', 'GET');
$router->add('/books/create', 'BookController', 'create', 'GET');
$router->add('/books/store', 'BookController', 'store', 'POST');
$router->add('/books/show/{id}', 'BookController', 'show', 'GET');
$router->add('/books/edit/{id}', 'BookController', 'edit', 'GET');
$router->add('/books/update/{id}', 'BookController', 'update', 'POST');
$router->add('/books/delete/{id}', 'BookController', 'delete', 'POST');
$router->add('/books/search', 'BookController', 'search', 'GET');
$router->add('/books/author/{author}', 'BookController', 'byAuthor', 'GET');
$router->add('/books/genre/{genre}', 'BookController', 'byGenre', 'GET');
$router->add('/api/books', 'BookController', 'apiIndex', 'GET');
$router->add('/api/books/{id}', 'BookController', 'apiShow', 'GET');
$router->add('/api/stats', 'BookController', 'stats', 'GET');

// Получаем текущий URL
$url = $_GET['url'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// Запускаем роутер
$router->dispatch($url, $method);
?>