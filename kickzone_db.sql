-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Июн 21 2026 г., 09:56
-- Версия сервера: 8.0.44
-- Версия PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kickzone_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `brands`
--

CREATE TABLE `brands` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(2, 'Adidas'),
(5, 'Asics'),
(11, 'Converse'),
(9, 'Dr. Martens'),
(4, 'New Balance'),
(1, 'Nike'),
(3, 'Puma'),
(6, 'Reebok'),
(10, 'Timberland'),
(12, 'Vans');

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `size` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `size`) VALUES
(7, 1, 14, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `sort_order`) VALUES
(18, 'Кроссовки', 'sneakers', 'Спортивная и повседневная обувь', 1),
(19, 'Кеды', 'keds', 'Классические кеды для повседневной носки', 2),
(20, 'Бутсы', 'boots', 'Футбольные бутсы и шиповки', 3),
(21, 'Сандалии', 'sandals', 'Летняя открытая обувь', 4),
(28, 'Ботинки', 'warm_boots', 'Утеплённая обувь для холодной погоды', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `colors`
--

CREATE TABLE `colors` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `colors`
--

INSERT INTO `colors` (`id`, `name`, `value`) VALUES
(1, 'Черный', 'black'),
(2, 'Белый', 'white'),
(3, 'Красный', 'red'),
(4, 'Синий', 'blue'),
(5, 'Серый', 'grey'),
(6, 'Зеленый', 'green'),
(7, 'Фиолетовый', 'purple'),
(8, 'Оранжевый', 'orange'),
(9, 'Желтый', 'yellow'),
(15, 'Розовый', 'pink'),
(16, 'Голубой', 'lightblue'),
(17, 'Тёмно-синий', 'navy'),
(18, 'Бежевый', 'beige'),
(19, 'Коричнеый', 'brown');

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `total`, `status`, `address`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'ORD-1781192402-1', 1, 53940.00, 'confirmed', 'г. Москва, ул. Тестовая, д. 1', '+7 999 123 45 67', '2026-06-11 15:40:02', '2026-06-16 12:08:13'),
(2, 'ORD-1781192404-1', 1, 17980.00, 'delivered', 'г. Москва, ул. Тестовая, д. 1', '+7 999 123 45 67', '2026-06-11 15:40:04', '2026-06-20 16:10:54'),
(15, 'ORD-1782018477-2', 2, 12500.00, 'shipped', 'авыfdsf', '+7 (430) 434-03-40', '2026-06-21 05:07:57', '2026-06-21 09:44:53');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `name`, `quantity`, `price`, `size`) VALUES
(1, 1, 13, 'Nike Air Max 90', 6, 8990.00, NULL),
(2, 2, 13, 'Nike Air Max 90', 2, 8990.00, NULL),
(18, 15, 11, 'Nike Jordan 4', 1, 12500.00, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('male','female','unisex') COLLATE utf8mb4_unicode_ci DEFAULT 'unisex',
  `color_id` int DEFAULT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `gender`, `color_id`, `slug`, `description`, `price`, `image`, `created_at`) VALUES
(11, 18, 1, 'Nike Jordan 4', 'male', 5, 'jordan4', 'jordan 4 is the best', 12500.00, '6a2ae9c09449f.png', '2026-06-11 16:58:31'),
(13, 18, 1, 'Nike Air Max 90', 'male', 1, 'male-nike-air-max-90', 'Классические кроссовки', 8990.00, '6a2c32fbcaab3.png', '2026-06-12 16:01:34'),
(14, 18, 2, 'Adidas Yeezy Boost', 'unisex', 1, 'unisex-adidas-yeezy-boost', 'Лимитированная коллекция', 24990.00, '6a2c32f0ef95a.png', '2026-06-12 16:01:34'),
(15, 18, 4, 'New Balance 574', 'unisex', 18, 'unisex-new-balance-574', 'Повседневные кроссовки', 6990.00, '6a2c32e8ad9e8.png', '2026-06-12 16:01:34'),
(16, 19, 3, 'Puma Suede Classic', 'female', 19, 'women-puma-suede-classic', 'Классика на каждый день', 5490.00, '6a2c32e13ad04.png', '2026-06-12 16:01:34'),
(17, 18, 5, 'Asics Gel-Kayano', 'unisex', 2, 'unisex-asics-gel-kayano', 'Для бега и тренировок', 12990.00, '6a2c32d84cb69.png', '2026-06-12 16:01:34'),
(20, 18, 1, 'Nike Air Max 270', 'unisex', 15, 'nike-air-max-270', 'Кроссовки Nike Air Max 270 с амортизацией и стильным дизайном', 12990.00, '6a32fd6a50197.png', '2026-06-17 18:50:06'),
(21, 18, 1, 'Nike Air Force 1', 'unisex', 4, 'nike-air-force-1', 'Культовые белые кроссовки Nike Air Force 1', 11990.00, '6a32fd7d939b9.png', '2026-06-17 18:50:06'),
(22, 18, 1, 'Nike Dunk Low', 'unisex', 17, 'nike-dunk-low', 'Модные кеды Nike Dunk Low в винтажном стиле', 9990.00, '6a32fd8ce0659.png', '2026-06-17 18:50:06'),
(23, 18, 1, 'Nike Air VaporMax', 'unisex', 3, 'nike-air-vapormax', 'Кроссовки Nike Air VaporMax с воздушной подушкой', 15990.00, '6a32fda521a99.png', '2026-06-17 18:50:06'),
(24, 18, 1, 'Nike Air Max 97', 'unisex', 5, 'nike-air-max-97', 'Кроссовки Nike Air Max 97 с серебряным акцентом', 13990.00, '6a32fdb4a9741.png', '2026-06-17 18:50:06'),
(25, 18, 2, 'Adidas Ultraboost', 'unisex', 15, 'adidas-ultraboost', 'Беговые кроссовки Adidas Ultraboost с максимальной амортизацией', 15990.00, '6a32fdca2efe4.png', '2026-06-17 18:50:06'),
(26, 18, 2, 'Adidas Stan Smith', 'unisex', 2, 'adidas-stan-smith', 'Классические белые кеды Adidas Stan Smith', 8990.00, '6a32fdd5793b8.png', '2026-06-17 18:50:06'),
(27, 18, 2, 'Adidas NMD R1', 'unisex', 1, 'adidas-nmd-r1', 'Модные кроссовки Adidas NMD R1 в городском стиле', 11990.00, '6a32fde031e66.png', '2026-06-17 18:50:06'),
(28, 18, 2, 'Adidas Forum Low', 'unisex', 18, 'adidas-forum-low', 'Классические кеды Adidas Forum с ретро-дизайном', 9490.00, '6a32fdf18c99c.png', '2026-06-17 18:50:06'),
(29, 19, 3, 'Puma Suede Classic', 'unisex', 3, 'puma-suede-classic-grey', 'Культовые кроссовки Puma Suede Classic из замши', 7990.00, '6a32fdffe320e.png', '2026-06-17 18:50:06'),
(30, 18, 3, 'Puma RS-X', 'unisex', 16, 'puma-rs-x', 'Модные кроссовки Puma RS-X с неоновым дизайном', 10990.00, '6a32fe0c7ab55.png', '2026-06-17 18:50:06'),
(31, 18, 3, 'Puma Future Rider', 'unisex', 2, 'puma-future-rider', 'Кроссовки Puma Future Rider в винтажном стиле', 8490.00, '6a32fe1e27fcc.png', '2026-06-17 18:50:06'),
(32, 18, 4, 'New Balance 990v5', 'unisex', 17, 'new-balance-990v5', 'Легендарные кроссовки New Balance 990v5 Made in USA', 16990.00, '6a32ff4d99db7.png', '2026-06-17 18:50:06'),
(33, 18, 4, 'New Balance 550', 'unisex', 16, 'new-balance-550', 'Кеды New Balance 550 в баскетбольном стиле', 10990.00, '6a32fe581b49c.png', '2026-06-17 18:50:06'),
(34, 18, 4, 'New Balance 327', 'female', 18, 'new-balance-327', 'Женские кроссовки New Balance 327', 8990.00, '6a32ff6b62224.png', '2026-06-17 18:50:06'),
(35, 18, 6, 'Reebok Club C 85', 'unisex', 18, 'reebok-club-c-85', 'Классические теннисные кеды Reebok Club C 85', 7490.00, '6a32ff88aba65.png', '2026-06-17 18:50:06'),
(36, 18, 6, 'Reebok Nano X2', 'unisex', 17, 'reebok-nano-x2', 'Кроссовки Reebok Nano X2 для тренировок', 10990.00, '6a32ff969178f.png', '2026-06-17 18:50:06'),
(37, 18, 5, 'Asics Gel-Kayano 27', 'male', 3, 'asics-gel-kayano-27', 'Профессиональные беговые кроссовки Asics Gel-Kayano', 14990.00, '6a32ffa400811.png', '2026-06-17 18:50:06'),
(38, 18, 5, 'Asics Gel-Quantum 180', 'female', 5, 'asics-gel-quantum-180', 'Женские беговые кроссовки Asics с гелевой амортизацией', 13990.00, '6a32ffb4b27b3.png', '2026-06-17 18:50:06'),
(39, 18, 5, 'Asics Gel-Nimbus', 'unisex', 1, 'asics-gel-nimbus', 'Беговые кроссовки Asics Gel-Nimbus', 12990.00, '6a32ffc1cf762.png', '2026-06-17 18:50:06'),
(40, 19, 12, 'Vans Old Skool', 'unisex', 8, 'vans-old-skool', 'Классические кеды Vans Old Skool с боковой полосой', 6990.00, '6a32ffd0407a0.png', '2026-06-17 18:50:06'),
(41, 19, 12, 'Vans Authentic', 'unisex', 4, 'vans-authentic', 'Легкие и удобные кеды Vans Authentic', 5990.00, '6a32ffdff3b85.png', '2026-06-17 18:50:06'),
(42, 19, 12, 'Vans Era', 'unisex', 1, 'vans-era', 'Кеды Vans Era на каждый день', 6490.00, '6a32ffefcf07f.png', '2026-06-17 18:50:06'),
(43, 19, 11, 'Converse Chuck Taylor All Star', 'unisex', 1, 'converse-chuck-taylor', 'Культовые кеды Converse Chuck Taylor All Star', 6490.00, '6a32fffd28d37.png', '2026-06-17 18:50:06'),
(44, 19, 11, 'Converse One Star', 'unisex', 18, 'converse-one-star', 'Кеды Converse One Star с звездой на боку', 6990.00, '6a330011db3df.png', '2026-06-17 18:50:06'),
(45, 19, 11, 'Converse Run Star Motion', 'female', 3, 'converse-run-star-motion', 'Модные кеды Converse на платформе', 8990.00, '6a3300296590b.png', '2026-06-17 18:50:06'),
(46, 20, 1, 'Nike Mercurial Superfly', 'male', 16, 'nike-mercurial-superfly', 'Футбольные бутсы Nike Mercurial Superfly', 14990.00, '6a32fd540c59f.png', '2026-06-17 18:50:06'),
(47, 20, 2, 'Adidas Predator Edge', 'male', 8, 'adidas-predator-edge', 'Футбольные бутсы Adidas Predator с контролем мяча', 13990.00, '6a32fd3e8933c.png', '2026-06-17 18:50:06'),
(48, 20, 3, 'Puma Future Z', 'unisex', 1, 'puma-future-z', 'Футбольные бутсы Puma Future Z', 12990.00, '6a32fd31478aa.png', '2026-06-17 18:50:06'),
(49, 20, 1, 'Nike Tiempo Legend', 'male', 2, 'nike-tiempo-legend', 'Кожаные футбольные бутсы Nike Tiempo Legend', 13490.00, '6a32fd23ef3c5.png', '2026-06-17 18:50:06'),
(50, 21, 2, 'Adidas Adilette', 'unisex', 1, 'adidas-adilette', 'Классические шлёпанцы Adidas Adilette', 4490.00, '6a32fd120a9f5.png', '2026-06-17 18:50:06'),
(51, 21, 1, 'Nike Benassi', 'unisex', 1, 'nike-benassi', 'Шлепанцы Nike Benassi с мягкой подошвой', 3990.00, '6a32fd004ac82.png', '2026-06-17 18:50:06'),
(52, 21, 3, 'Puma Leadcat', 'unisex', 3, 'puma-leadcat', 'Мягкие шлепанцы Puma Leadcat', 3990.00, '6a32fcf206c0d.png', '2026-06-17 18:50:06'),
(53, 21, 4, 'New Balance Slide', 'unisex', 1, 'new-balance-slide', 'Шлепанцы New Balance Slide', 4490.00, '6a32fce51ad06.png', '2026-06-17 18:50:06'),
(54, 28, 10, 'Timberland 6 Inch Boot', 'male', 19, 'timberland-6-inch', 'Культовые жёлтые ботинки Timberland', 15990.00, '6a32fcd55d2cb.png', '2026-06-17 18:50:06'),
(55, 28, 9, 'Dr. Martens 1460', 'unisex', 1, 'dr-martens-1460', 'Классические ботинки Dr. Martens 1460', 14990.00, '6a32fcc558595.png', '2026-06-17 18:50:06'),
(56, 28, 1, 'Nike Air Force 1 Boot', 'male', 6, 'nike-air-force-1-boot', 'Ботинки Nike Air Force 1 Boot в уличном стиле', 13990.00, '6a32ff5c8236c.png', '2026-06-17 18:50:06'),
(57, 28, 2, 'Adidas Terrex', 'unisex', 1, 'adidas-terrex', 'Треккинговые ботинки Adidas Terrex', 12990.00, '6a32fca17b86f.png', '2026-06-17 18:50:06');

-- --------------------------------------------------------

--
-- Структура таблицы `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `size_id` int NOT NULL,
  `stock` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_id`, `size_id`, `stock`) VALUES
(112, 17, 22, 3),
(113, 17, 23, 2),
(114, 17, 28, 4),
(115, 17, 30, 8),
(119, 15, 23, 4),
(120, 15, 25, 3),
(121, 15, 27, 3),
(122, 15, 29, 5),
(123, 14, 23, 5),
(124, 14, 25, 3),
(125, 14, 26, 5),
(126, 14, 27, 5),
(127, 13, 26, 2),
(128, 13, 27, 5),
(129, 13, 30, 1),
(130, 13, 33, 1),
(312, 57, 27, 4),
(313, 57, 28, 6),
(314, 57, 29, 5),
(315, 57, 30, 3),
(324, 53, 26, 6),
(325, 53, 27, 8),
(326, 53, 28, 10),
(327, 53, 29, 6),
(328, 52, 26, 5),
(329, 52, 27, 7),
(330, 52, 28, 10),
(331, 52, 29, 6),
(332, 51, 27, 10),
(333, 51, 28, 12),
(334, 51, 29, 8),
(335, 51, 30, 6),
(336, 50, 26, 8),
(337, 50, 27, 10),
(338, 50, 28, 12),
(339, 50, 29, 8),
(340, 49, 27, 3),
(341, 49, 28, 4),
(342, 49, 29, 5),
(343, 49, 30, 3),
(344, 48, 26, 3),
(345, 48, 27, 5),
(346, 48, 28, 4),
(347, 48, 29, 3),
(348, 47, 28, 4),
(349, 47, 29, 6),
(350, 47, 30, 3),
(351, 47, 31, 2),
(352, 46, 27, 3),
(353, 46, 28, 5),
(354, 46, 29, 4),
(355, 46, 30, 2),
(356, 46, 31, 1),
(357, 20, 26, 5),
(358, 20, 27, 7),
(359, 20, 28, 8),
(360, 20, 29, 6),
(361, 20, 30, 4),
(362, 21, 25, 3),
(363, 21, 26, 6),
(364, 21, 27, 8),
(365, 21, 28, 10),
(366, 21, 29, 7),
(367, 22, 26, 4),
(368, 22, 27, 5),
(369, 22, 28, 6),
(370, 22, 29, 5),
(371, 23, 26, 4),
(372, 23, 27, 6),
(373, 23, 28, 5),
(374, 23, 29, 3),
(375, 23, 30, 2),
(376, 24, 26, 3),
(377, 24, 27, 5),
(378, 24, 28, 7),
(379, 24, 29, 4),
(380, 24, 30, 3),
(381, 25, 26, 3),
(382, 25, 27, 5),
(383, 25, 28, 7),
(384, 25, 29, 6),
(385, 25, 30, 4),
(386, 26, 25, 4),
(387, 26, 26, 6),
(388, 26, 27, 8),
(389, 26, 28, 5),
(390, 26, 29, 3),
(391, 27, 26, 5),
(392, 27, 27, 7),
(393, 27, 28, 6),
(394, 27, 29, 4),
(395, 28, 25, 3),
(396, 28, 26, 5),
(397, 28, 27, 7),
(398, 28, 28, 6),
(399, 28, 29, 4),
(404, 30, 27, 3),
(405, 30, 28, 5),
(406, 30, 29, 4),
(407, 31, 25, 3),
(408, 31, 26, 5),
(409, 31, 27, 7),
(410, 31, 28, 6),
(416, 33, 25, 3),
(417, 33, 26, 5),
(418, 33, 27, 7),
(419, 33, 28, 6),
(420, 33, 29, 4),
(421, 32, 26, 2),
(422, 32, 27, 4),
(423, 32, 28, 6),
(424, 32, 29, 5),
(425, 32, 30, 3),
(426, 56, 27, 4),
(427, 56, 28, 6),
(428, 56, 29, 5),
(429, 56, 30, 3),
(430, 34, 22, 3),
(431, 34, 23, 5),
(432, 34, 24, 7),
(433, 34, 25, 5),
(434, 34, 26, 3),
(435, 35, 25, 5),
(436, 35, 26, 7),
(437, 35, 27, 8),
(438, 35, 28, 6),
(439, 36, 26, 4),
(440, 36, 27, 6),
(441, 36, 28, 5),
(442, 36, 29, 3),
(443, 37, 27, 3),
(444, 37, 28, 5),
(445, 37, 29, 6),
(446, 37, 30, 4),
(447, 37, 31, 2),
(448, 38, 22, 3),
(449, 38, 23, 5),
(450, 38, 24, 6),
(451, 38, 25, 4),
(452, 39, 26, 4),
(453, 39, 27, 6),
(454, 39, 28, 5),
(455, 39, 29, 4),
(483, 55, 25, 3),
(484, 55, 26, 5),
(485, 55, 27, 6),
(486, 55, 28, 4),
(487, 54, 27, 3),
(488, 54, 28, 5),
(489, 54, 29, 4),
(490, 54, 30, 3),
(491, 45, 22, 3),
(492, 45, 23, 5),
(493, 45, 24, 6),
(494, 45, 25, 4),
(495, 11, 30, 7),
(496, 11, 32, 3),
(497, 11, 33, 1),
(498, 16, 22, 3),
(499, 16, 23, 7),
(500, 16, 24, 2),
(501, 29, 26, 5),
(502, 29, 27, 6),
(503, 29, 28, 7),
(504, 29, 29, 4),
(505, 40, 26, 6),
(506, 40, 27, 8),
(507, 40, 28, 10),
(508, 40, 29, 7),
(509, 40, 30, 5),
(510, 41, 25, 4),
(511, 41, 26, 6),
(512, 41, 27, 8),
(513, 41, 28, 5),
(514, 42, 25, 3),
(515, 42, 26, 5),
(516, 42, 27, 7),
(517, 42, 28, 6),
(518, 42, 29, 4),
(519, 43, 24, 3),
(520, 43, 25, 5),
(521, 43, 26, 8),
(522, 43, 27, 7),
(523, 43, 28, 6),
(524, 44, 25, 4),
(525, 44, 26, 6),
(526, 44, 27, 5),
(527, 44, 28, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `sizes`
--

CREATE TABLE `sizes` (
  `id` int NOT NULL,
  `value` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sizes`
--

INSERT INTO `sizes` (`id`, `value`, `sort_order`) VALUES
(21, '35', 1),
(22, '36', 2),
(23, '37', 3),
(24, '38', 4),
(25, '39', 5),
(26, '40', 6),
(27, '41', 7),
(28, '42', 8),
(29, '43', 9),
(30, '44', 10),
(31, '45', 11),
(32, '46', 12),
(33, '47', 13);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `full_name`, `phone`, `address`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@test.com', 'Администратор', NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-06-11 15:32:32'),
(2, 'test', 'test@mail.ru', 'valya', '+7 (430) 434-03-40', 'авыfdsf', '$2y$10$OnQdhxU4ABmiK/ClJlH8FOKhRbkub0seWTjxt9L//V83eHrvkWrD6', 'user', '2026-06-15 15:41:41');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cart` (`user_id`,`product_id`,`size`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Индексы таблицы `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `value` (`value`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

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
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `idx_slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_size` (`product_id`,`size_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Индексы таблицы `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `value` (`value`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT для таблицы `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=528;

--
-- AUTO_INCREMENT для таблицы `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sizes_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
