<?php
// public/index.php
session_start();

require_once "../app/core/Router.php";
require_once "../app/controllers/BookController.php";

$router = new Router();

// Определяем маршруты
$router->add('/', 'BookController', 'index', 'GET');
$router->add('/books/create', 'BookController', 'create', 'GET');
$router->add('/books/store', 'BookController', 'store', 'POST');
$router->add('/books/show/{id}', 'BookController', 'show', 'GET');
$router->add('/books/delete/{id}', 'BookController', 'delete', 'POST');
$router->add('/books/search', 'BookController', 'search', 'GET');

// Получаем текущий URL
$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Убираем путь до public
$base = '/book-catalog/public';
if (strpos($url, $base) === 0) {
    $url = substr($url, strlen($base));
}

$router->dispatch($url ?: '/', $method);