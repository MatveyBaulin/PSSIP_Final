<?php
// Параметры подключения к базе данных
$servername = "localhost";
$username = "root";     // По умолчанию для XAMPP
$password = "";         // По умолчанию для XAMPP
$dbname = "mysql";      // Имя вашей базы данных

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Устанавливаем кодировку для корректного отображения русских символов
$conn->set_charset("utf8");

// Теперь можно использовать $conn в основном файле
?>