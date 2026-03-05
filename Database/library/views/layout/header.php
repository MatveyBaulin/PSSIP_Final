<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Библиотека'; ?></title>
    <link rel="stylesheet" href="/library/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="/library/">📚 Моя библиотека</a></h1>
            
            <nav>
                <a href="/library/">Главная</a>
                <a href="/library/books/create">Добавить книгу</a>
            </nav>
            
            <div class="search">
                <form action="/library/books/search" method="GET">
                    <input type="text" name="q" placeholder="Поиск книг..." 
                           value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button type="submit">🔍</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
            <div class="alert error">
                <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>