<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог книг</title>
    <link rel="stylesheet" href="/book-catalog/public/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="/book-catalog/public/">📚 Каталог книг</a></h1>
            
            <nav>
                <a href="/book-catalog/public/">Главная</a>
                <a href="/book-catalog/public/books/create">Добавить книгу</a>
            </nav>
            
            <div class="search">
                <form action="/book-catalog/public/books/search" method="GET">
                    <input type="text" name="q" placeholder="Поиск книг...">
                    <button type="submit">Найти</button>
                </form>
            </div>
        </div>
    </header>
    
    <main class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>