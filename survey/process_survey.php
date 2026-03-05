<?php

// Укажите путь к файлу, где будут храниться результаты
// файл будет создан в той же директории, что и этот PHP скрипт
$resultsFile = 'results.txt';

// Проверяем, был ли запрос методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Получаем данные из формы
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : 'Аноним';
    $q1 = isset($_POST['q1']) ? htmlspecialchars(trim($_POST['q1'])) : 'не отвечено';

    // Обработка чекбоксов (они могут быть не выбраны)
    $features = isset($_POST['features']) && is_array($_POST['features']) ? $_POST['features'] : [];
    // Преобразуем массив выбранных функций в строку
    $featuresString = !empty($features) ? implode(', ', array_map('htmlspecialchars', $features)) : 'ничего не выбрано';

    $comment = isset($_POST['comment']) ? htmlspecialchars(trim($_POST['comment'])) : 'нет комментариев';

    // Форматируем дату и время для записи
    $timestamp = date("Y-m-d H:i:s");

    // Создаем строку для записи в файл
    $data = "[{$timestamp}] | Имя: {$name} | Удовлетворенность: {$q1} | Желаемые функции: {$featuresString} | Комментарии: {$comment}\n";

    // Пытаемся открыть файл для добавления данных (режим 'a' - append)
    // Если файла нет, он будет создан
    $fileHandle = fopen($resultsFile, 'a');

    if ($fileHandle) {
        // Записываем данные
        if (fwrite($fileHandle, $data) !== false) {
            // Закрываем файл
            fclose($fileHandle);
            // Перенаправляем пользователя на страницу с сообщением об успехе
            header("Location: success.html");
            exit();
        } else {
            // Ошибка записи в файл
            echo "Произошла ошибка при записи результатов в файл.";
            fclose($fileHandle); // Важно закрыть файл даже при ошибке
        }
    } else {
        // Не удалось открыть файл
        echo "Произошла ошибка при открытии файла для записи результатов.";
    }
} else {
    // Если запрос сделан не методом POST (например, прямой переход по URL)
    echo "Этот скрипт предназначен для обработки данных формы.";
}

?>
