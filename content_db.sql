-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 05 2026 г., 17:39
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `content_db`
--
CREATE DATABASE IF NOT EXISTS `content_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `content_db`;

-- --------------------------------------------------------

--
-- Структура таблицы `text_content`
--

DROP TABLE IF EXISTS `text_content`;
CREATE TABLE `text_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `text_content`
--

INSERT INTO `text_content` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'Приветствие', 'Добро пожаловать на наш сайт! Здесь вы найдете много интересной информации.', '2026-03-01 22:23:53'),
(2, 'О нас', 'Мы занимаемся разработкой веб-приложений с использованием PHP и MySQL.', '2026-03-01 22:23:53'),
(3, 'Контакты', 'Наш адрес: г. Москва, ул. Программистов, д. 1\nТелефон: +7 (123) 456-78-90\nEmail: info@example.com', '2026-03-01 22:23:53');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `text_content`
--
ALTER TABLE `text_content`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `text_content`
--
ALTER TABLE `text_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
