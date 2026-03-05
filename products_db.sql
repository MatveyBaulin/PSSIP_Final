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
-- База данных: `products_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('new','processing','completed','cancelled') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `comment`, `total_amount`, `status`, `created_at`) VALUES
(1, 'ORD-20260302-9531DF', 'Баулин Матвей Олегович', 'baulinm2006@gmail.com', '+375298393444', 'г. Гродно, проспект Клецкова 11, 266', 'Буду ждать!', 40600.00, 'new', '2026-03-01 23:08:57'),
(2, 'ORD-20260304-3D9468', 'Баулин Матвей Олегович', 'baulinm2006@gmail.com', '+375298393444', '-', '-', 40600.00, 'new', '2026-03-04 11:40:03'),
(3, 'ORD-20260305-EC9F99', 'Баулин Матвей Олегович', 'baulinm2006@gmail.com', '+375298393444', '-', '', 20700.00, 'new', '2026-03-05 08:41:50'),
(4, 'ORD-20260305-A70404', 'Баулин Матвей Олегович', 'baulinm2006@gmail.com', '+375298393444', '-', 'Буду рад с вами поработать!', 55500.00, 'new', '2026-03-05 13:35:06');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 8, 'HTML/CSS для начинающих', 4900.00, 1, 4900.00),
(2, 1, 9, 'JavaScript с нуля', 7900.00, 1, 7900.00),
(3, 1, 12, 'Laravel Framework', 19900.00, 1, 19900.00),
(4, 1, 11, 'PHP для начинающих', 7900.00, 1, 7900.00),
(5, 2, 9, 'JavaScript с нуля', 7900.00, 1, 7900.00),
(6, 2, 8, 'HTML/CSS для начинающих', 4900.00, 1, 4900.00),
(7, 2, 12, 'Laravel Framework', 19900.00, 1, 19900.00),
(8, 2, 11, 'PHP для начинающих', 7900.00, 1, 7900.00),
(9, 3, 8, 'HTML/CSS для начинающих', 4900.00, 1, 4900.00),
(10, 3, 9, 'JavaScript с нуля', 7900.00, 1, 7900.00),
(11, 3, 11, 'PHP для начинающих', 7900.00, 1, 7900.00),
(12, 4, 8, 'HTML/CSS для начинающих', 4900.00, 1, 4900.00),
(13, 4, 9, 'JavaScript с нуля', 7900.00, 1, 7900.00),
(14, 4, 12, 'Laravel Framework', 19900.00, 1, 19900.00),
(15, 4, 11, 'PHP для начинающих', 7900.00, 1, 7900.00),
(16, 4, 3, 'Python, продвинутый уровень', 14900.00, 1, 14900.00);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` float NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `level` enum('Начальный','Средний','Продвинутый') DEFAULT 'Начальный',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `duration`, `category`, `level`, `created_at`) VALUES
(1, 'Введение в программирование', 0.00, 0.5, 'Основы', 'Начальный', '2026-03-01 22:43:17'),
(2, 'Основы программирования на Python', 9900.00, 1, 'Python', 'Начальный', '2026-03-01 22:43:17'),
(3, 'Python, продвинутый уровень', 14900.00, 2.5, 'Python', 'Продвинутый', '2026-03-01 22:43:17'),
(4, 'Сети + фреймворк Flask', 14900.00, 2.5, 'Веб-разработка', 'Средний', '2026-03-01 22:43:17'),
(5, 'Базы данных', 14900.00, 2, 'Базы данных', 'Средний', '2026-03-01 22:43:17'),
(6, 'Фреймворк Django', 14900.00, 2, 'Веб-разработка', 'Продвинутый', '2026-03-01 22:43:17'),
(7, 'Разработка \"боевого\" проекта', 9900.00, 1.5, 'Веб-разработка', 'Продвинутый', '2026-03-01 22:43:17'),
(8, 'HTML/CSS для начинающих', 4900.00, 1, 'Веб-разработка', 'Начальный', '2026-03-01 22:43:17'),
(9, 'JavaScript с нуля', 7900.00, 2, 'Веб-разработка', 'Начальный', '2026-03-01 22:43:17'),
(10, 'React.js', 14900.00, 2.5, 'Веб-разработка', 'Продвинутый', '2026-03-01 22:43:17'),
(11, 'PHP для начинающих', 7900.00, 1.5, 'Backend', 'Начальный', '2026-03-01 22:43:17'),
(12, 'Laravel Framework', 19900.00, 3, 'Backend', 'Продвинутый', '2026-03-01 22:43:17'),
(13, 'Алгоритмы и структуры данных', 9900.00, 2, 'Основы', 'Средний', '2026-03-01 22:43:17');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created` (`created_at`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_price` (`price`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_level` (`level`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
