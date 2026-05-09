-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2026 at 12:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `furqanstore_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_user_orders` (IN `p_user_id` INT)   BEGIN
    SELECT * FROM orders 
    WHERE user_id = p_user_id 
    ORDER BY created_at DESC;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_calculate_tax` (`amount` DECIMAL(10,2)) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN
    RETURN amount * 0.05;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(28, 14, 125, 1, '2026-05-05 12:09:30'),
(29, 14, 133, 1, '2026-05-05 12:09:34'),
(30, 14, 129, 1, '2026-05-05 12:09:36'),
(31, 1, 145, 1, '2026-05-06 05:44:14'),
(49, 20, 140, 1, '2026-05-07 03:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `created_at`) VALUES
(1, 'Electronics', 'electronics', 'fas fa-laptop', '2026-04-15 13:02:56'),
(2, 'Fashion', 'fashion', 'fas fa-tshirt', '2026-04-15 13:02:56'),
(3, 'Footwear', 'footwear', 'fas fa-shoe-prints', '2026-04-15 13:02:56'),
(4, 'Audio', 'audio', 'fas fa-headphones', '2026-04-15 13:02:56'),
(5, 'Appliances', 'appliances', 'fas fa-blender', '2026-04-15 13:02:56'),
(6, 'Sports', 'sports', 'fas fa-bicycle', '2026-04-15 13:02:56');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 'sehar', 'sehar@gmail.com', 'order', 'Your product quality is good.', 0, '2026-05-01 17:10:51'),
(2, 'M.Furqan', 'furqan@gmail.com', 'order', 'Your Quality of product is amazing', 0, '2026-05-04 15:33:47'),
(3, 'M.Furqan', 'furqan@gmail.com', 'order', 'good', 0, '2026-05-05 09:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'cod',
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `payment_method`, `shipping_address`, `created_at`) VALUES
(1, 8, 12499.00, 'pending', 'cod', 'Wahdat Road ,Lahore', '2026-04-15 18:07:28'),
(2, 10, 36497.00, 'pending', 'cod', 'Sabzazar Lahore Pakistan', '2026-04-17 07:42:11'),
(3, 13, 44496.00, 'pending', 'cod', 'Sabzazar lahore,pakistan', '2026-05-01 17:05:29'),
(4, 14, 20498.00, 'delivered', 'cod', 'DHA ,Lahore,Pakistan', '2026-05-05 10:32:49'),
(5, 14, 12499.00, 'delivered', 'cod', 'DHA,Lahore,Pakistan', '2026-05-05 10:38:26'),
(6, 4, 6500.00, 'delivered', 'cod', 'margazar road,lahore', '2026-05-05 11:13:30'),
(7, 14, 42300.00, 'delivered', 'cod', 'Multan Road,Lahore', '2026-05-05 12:03:30'),
(8, 14, 6500.00, 'delivered', 'cod', 'Multan Road,Lahore', '2026-05-05 12:06:20'),
(9, 4, 153500.00, 'delivered', 'cod', 'wahat road,lahore', '2026-05-06 07:04:06'),
(10, 4, 220000.00, 'delivered', 'cod', 'multan road,lahore', '2026-05-06 07:07:24'),
(11, 4, 4500.00, 'delivered', 'cod', 'hanjarwal,lahore', '2026-05-06 07:17:27'),
(12, 4, 9500.00, 'delivered', 'cod', 'nishter colony', '2026-05-06 07:21:01'),
(13, 19, 20000.00, 'delivered', 'cod', 'Wahdat Road,Lahore', '2026-05-06 15:00:54'),
(14, 19, 59500.00, 'delivered', 'cod', 'Wahdat Road,Lahore', '2026-05-06 15:02:37'),
(15, 19, 75000.00, 'delivered', 'cod', 'wahdat Road ,lahore', '2026-05-06 15:04:17'),
(16, 19, 35000.00, 'delivered', 'cod', 'wahdat road ,lahore', '2026-05-06 15:15:48'),
(18, 19, 5956500.00, 'delivered', 'cod', 'Wahdat Road ,Lahore', '2026-05-06 15:19:56'),
(19, 3, 13000.00, 'pending', 'cod', 'nistar coloney', '2026-05-07 09:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `vendor_earning` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `vendor_id`, `quantity`, `price`, `vendor_earning`, `created_at`) VALUES
(12, 7, 145, 3, 1, 4800.00, 4320.00, '2026-05-05 12:03:30'),
(13, 7, 144, 11, 1, 6500.00, 5850.00, '2026-05-05 12:03:30'),
(14, 7, 142, 11, 1, 9500.00, 8550.00, '2026-05-05 12:03:30'),
(15, 7, 141, 3, 1, 3500.00, 3150.00, '2026-05-05 12:03:30'),
(16, 7, 138, 11, 1, 18000.00, 16200.00, '2026-05-05 12:03:30'),
(17, 8, 144, 11, 1, 6500.00, 5850.00, '2026-05-05 12:06:20'),
(18, 9, 141, 3, 1, 3500.00, 3150.00, '2026-05-06 07:04:06'),
(19, 9, 133, 3, 2, 75000.00, 135000.00, '2026-05-06 07:04:06'),
(20, 10, 132, 11, 4, 55000.00, 198000.00, '2026-05-06 07:07:24'),
(21, 11, 139, 3, 1, 4500.00, 4050.00, '2026-05-06 07:17:27'),
(22, 12, 142, 11, 1, 9500.00, 8550.00, '2026-05-06 07:21:01'),
(23, 13, 140, 11, 2, 5500.00, 9900.00, '2026-05-06 15:00:54'),
(24, 13, 139, 3, 2, 4500.00, 8100.00, '2026-05-06 15:00:54'),
(25, 14, 132, 11, 1, 55000.00, 49500.00, '2026-05-06 15:02:37'),
(26, 14, 139, 3, 1, 4500.00, 4050.00, '2026-05-06 15:02:37'),
(27, 15, 133, 3, 1, 75000.00, 67500.00, '2026-05-06 15:04:17'),
(28, 16, 135, 3, 1, 35000.00, 31500.00, '2026-05-06 15:15:48'),
(30, 18, 144, 11, 1, 6500.00, 5850.00, '2026-05-06 15:19:56'),
(31, 18, 123, 11, 7, 850000.00, 5355000.00, '2026-05-06 15:19:56'),
(32, 19, 144, 11, 2, 6500.00, 11700.00, '2026-05-07 09:45:17');

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `tr_after_order_item_insert` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
    DECLARE new_stock INT;

    UPDATE products
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;

    SELECT stock INTO new_stock FROM products WHERE id = NEW.product_id;

    IF new_stock <= 0 THEN
        UPDATE products
        SET badge = 'out_of_stock', status = 'inactive'
        WHERE id = NEW.product_id;

    ELSEIF new_stock <= 5 THEN
        UPDATE products
        SET badge = 'low_stock', status = 'active'
        WHERE id = NEW.product_id;

    ELSE
        UPDATE products
        SET badge = NULL
        WHERE id = NEW.product_id AND badge IN ('low_stock', 'out_of_stock');
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_before_order_item_insert` BEFORE INSERT ON `order_items` FOR EACH ROW BEGIN
    DECLARE available_stock INT;
    SELECT stock INTO available_stock FROM products WHERE id = NEW.product_id;

    IF available_stock <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot place order: Product is out of stock.';
    END IF;

    IF NEW.quantity > available_stock THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot place order: Requested quantity exceeds available stock.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `reviews` int(11) DEFAULT 0,
  `badge` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image_url`, `category_id`, `vendor_id`, `rating`, `reviews`, `badge`, `status`, `created_at`, `updated_at`) VALUES
(118, 'MacBook Pro M3', 'Professional laptop for creators and developers.', 450000.00, 15, 'https://loremflickr.com/400/400/macbook,pro?lock=318', 1, 3, 4.3, 123, 'best', 'active', '2026-05-05 11:36:54', '2026-05-05 11:43:25'),
(119, 'Nike Air Max', 'Comfortable and stylish athletic footwear.', 35000.00, 30, 'https://loremflickr.com/400/400/nike,air?lock=319', 3, 3, 4.0, 306, 'hot', 'active', '2026-05-05 11:36:54', '2026-05-05 11:43:25'),
(120, 'Leather Jacket', 'Premium genuine leather jacket for men.', 18000.00, 25, 'https://loremflickr.com/400/400/leather,jacket?lock=320', 2, 11, 4.2, 417, 'new', 'active', '2026-05-05 11:36:54', '2026-05-05 11:43:25'),
(121, 'Rolex Submariner', 'Luxury Swiss timepiece for formal wear.', 2500000.00, 5, 'https://loremflickr.com/400/400/rolex,submariner?lock=321', 2, 11, 4.5, 319, 'best', 'active', '2026-05-05 11:36:54', '2026-05-05 11:43:25'),
(122, 'PlayStation 5', 'Next-generation gaming console.', 165000.00, 10, 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=400', 1, 11, 4.8, 86, 'hot', 'active', '2026-05-05 11:36:54', '2026-05-05 11:53:09'),
(123, 'Canon DSLR R5', 'Professional mirrorless camera for photography.', 850000.00, 1, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=400', 1, 11, 4.4, 369, 'low_stock', 'active', '2026-05-05 11:36:54', '2026-05-06 15:19:56'),
(124, 'Coffee Machine', 'Automatic espresso and latte maker.', 45000.00, 12, 'https://loremflickr.com/400/400/coffee,machine?lock=324', 5, 3, 4.9, 129, 'sale', 'active', '2026-05-05 11:36:54', '2026-05-05 11:43:26'),
(125, 'Gaming Mouse', 'High-DPI wireless RGB gaming mouse.', 8500.00, 50, 'https://loremflickr.com/400/400/gaming,mouse?lock=325', 1, 3, 4.0, 273, NULL, 'active', '2026-05-05 11:36:54', '2026-05-05 11:43:26'),
(126, 'Mechanical Keyboard', 'Tactile mechanical keyboard with RGB.', 15000.00, 40, 'https://loremflickr.com/400/400/mechanical,keyboard?lock=326', 1, 11, 4.9, 290, 'hot', 'active', '2026-05-05 11:36:54', '2026-05-05 11:43:26'),
(127, 'Designer Sunglasses', 'UV protection luxury aviator sunglasses.', 12000.00, 60, 'https://loremflickr.com/400/400/designer,sunglasses?lock=327', 2, 3, 4.2, 102, NULL, 'active', '2026-05-05 11:36:55', '2026-05-05 11:43:26'),
(128, 'Winter Coat', 'Heavy insulated coat for extreme cold.', 22000.00, 15, 'https://loremflickr.com/400/400/winter,coat?lock=328', 2, 11, 4.3, 433, 'sale', 'active', '2026-05-05 11:36:55', '2026-05-05 11:43:26'),
(129, 'Adidas Tracksuit', 'Classic three-stripe sports tracksuit.', 15000.00, 35, 'https://loremflickr.com/400/400/adidas,tracksuit?lock=329', 6, 3, 4.4, 235, NULL, 'active', '2026-05-05 11:36:55', '2026-05-05 11:43:26'),
(130, 'Yoga Mat', 'Eco-friendly non-slip exercise mat.', 3500.00, 100, 'https://loremflickr.com/400/400/yoga,mat?lock=330', 6, 11, 4.9, 411, NULL, 'active', '2026-05-05 11:36:55', '2026-05-05 11:43:26'),
(132, 'Electric Guitar', 'Solid body electric guitar with amplifier.', 55000.00, 5, 'https://loremflickr.com/400/400/electric,guitar?lock=332', 1, 11, 4.9, 253, 'new', 'active', '2026-05-05 11:36:55', '2026-05-06 15:02:37'),
(133, 'Wireless Headphones', 'Noise-cancelling over-ear headphones.', 75000.00, 22, 'https://loremflickr.com/400/400/wireless,headphones?lock=333', 4, 3, 4.8, 229, 'best', 'active', '2026-05-05 11:36:55', '2026-05-06 15:04:17'),
(135, 'Air Purifier', 'HEPA filter purifier for home use.', 35000.00, 17, 'https://loremflickr.com/400/400/air,purifier?lock=335', 5, 3, 4.6, 256, NULL, 'active', '2026-05-05 11:36:55', '2026-05-06 15:15:48'),
(137, 'Mountain Bike', 'Durable off-road bicycle with 21 gears.', 45000.00, 12, 'https://loremflickr.com/400/400/mountain,bike?lock=337', 6, 3, 4.4, 494, NULL, 'active', '2026-05-05 11:36:55', '2026-05-05 11:43:26'),
(138, 'Camping Tent', 'Waterproof 4-person camping tent.', 18000.00, 19, 'https://loremflickr.com/400/400/camping,tent?lock=338', 6, 11, 4.7, 58, NULL, 'active', '2026-05-05 11:36:55', '2026-05-05 12:03:30'),
(139, 'Formal Shirt', 'Slim-fit luxury cotton formal shirt.', 4500.00, 76, 'https://loremflickr.com/400/400/formal,shirt?lock=339', 2, 3, 4.9, 390, NULL, 'active', '2026-05-05 11:36:55', '2026-05-06 15:02:37'),
(140, 'Denim Jeans', 'Classic blue slim-fit denim jeans.', 5500.00, 88, 'https://loremflickr.com/400/400/denim,jeans?lock=340', 2, 11, 4.7, 228, NULL, 'active', '2026-05-05 11:36:56', '2026-05-06 15:00:54'),
(141, 'Canvas Shoes', 'Lightweight casual canvas sneakers.', 3500.00, 118, 'https://loremflickr.com/400/400/canvas,shoes?lock=341', 3, 3, 4.3, 147, NULL, 'active', '2026-05-05 11:36:56', '2026-05-06 07:04:06'),
(142, 'Bluetooth Speaker', 'Portable waterproof wireless speaker.', 9500.00, 68, 'https://loremflickr.com/400/400/bluetooth,speaker?lock=342', 4, 11, 4.9, 148, 'sale', 'active', '2026-05-05 11:36:56', '2026-05-06 07:21:01'),
(144, 'Backpack', 'Durable travel and laptop backpack.', 6500.00, 80, 'https://loremflickr.com/400/400/backpack?lock=344', 2, 11, 5.0, 412, NULL, 'active', '2026-05-05 11:36:56', '2026-05-07 09:45:17'),
(145, 'Electric Kettle', 'Fast-boiling stainless steel kettle.', 4800.00, 64, 'https://loremflickr.com/400/400/electric,kettle?lock=345', 5, 3, 4.7, 236, NULL, 'active', '2026-05-05 11:36:56', '2026-05-05 12:03:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','vendor','customer') DEFAULT 'customer',
  `status` enum('active','pending','suspended') DEFAULT 'pending',
  `vendor_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `role`, `status`, `vendor_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@furqan.com', '03000000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active', NULL, '2026-04-15 13:02:56', '2026-04-15 13:02:56'),
(2, 'Admin User', 'admin@furqan.com', '03000000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NULL, '2026-04-15 13:02:56', '2026-04-15 13:02:56'),
(3, 'Nike Store', 'vendor@furqan.com', '03000000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendor', 'active', 'Nike Store', '2026-04-15 13:02:56', '2026-04-15 13:02:56'),
(4, 'Customer', 'customer@furqan.com', '03000000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'active', NULL, '2026-04-15 13:02:56', '2026-04-15 13:02:56'),
(5, 'Muhammad Furqan', 'furqan@gmail.com', '03227200433', 'dd27f009c25fb8f199129f46776fccd4', 'customer', 'active', NULL, '2026-04-15 13:15:01', '2026-04-15 13:15:01'),
(6, 'Muhammad ahmad', 'Ahmad@gmail.com', '0322700533', 'ec6d9818fb26b09eac54b64235eacdd1', 'customer', 'active', NULL, '2026-04-15 14:33:09', '2026-04-15 14:33:09'),
(7, 'Muhammad Usman', 'Usman@gmail.com', '03227200433', 'ec6d9818fb26b09eac54b64235eacdd1', 'customer', 'active', NULL, '2026-04-15 17:29:16', '2026-04-15 17:29:16'),
(8, 'Tayyaba', 'Tayyaba@gmail.com', '0322700533', '$2y$10$Y5SkUISEi7nXbCLF9GkgWOF9AvR/caKEn3PYlWZLxE.bZFEC3gYXS', 'customer', 'active', NULL, '2026-04-15 18:06:20', '2026-04-15 18:06:20'),
(9, 'Arshad ali', 'Arshad@gmail.com', '03227200433', '$2y$10$y56Ca7B8kLELB8S2ShOPkec2qvOpWt3kEpng.Iy1chRfetywRCFfa', 'customer', 'active', NULL, '2026-04-16 07:08:38', '2026-04-16 07:08:38'),
(10, 'M.amjad', 'amjad@gmail.com', '0322700533', '$2y$10$NcTqp0QirUVV10ppimgM5.dPrrYlHjfIiPCgAmM9y9ww.l6g77si6', 'customer', 'active', NULL, '2026-04-17 07:40:57', '2026-04-17 07:40:57'),
(11, 'M.awais', 'awais@gmail.com', '0322700533', '$2y$10$ap2xFFIyFy8AoEt9dyBvXuc6Xlg0SNQszCxl5iAGcVw815AtsOu9u', 'vendor', 'active', NULL, '2026-04-17 07:44:52', '2026-04-17 07:54:34'),
(12, 'Muazzam Bhatti', 'muazam@gmail.com', '03116439915', '$2y$10$CqSM6gRZarNefh/l7FDbnu1QAXn472QiK3DSpyzBGQnl3g5u40QAy', 'customer', 'active', NULL, '2026-04-23 07:56:52', '2026-04-23 07:56:52'),
(13, 'Furqan', 'Furqanz@gmail.com', '03227200433', '$2y$10$l52uZSXQQCa0R1sOp/kufuGrl/BYYnGhuCuDJ1w.yNphsoUsyfvre', 'customer', 'active', NULL, '2026-05-01 17:04:01', '2026-05-01 17:04:01'),
(14, 'Fatima', 'fatima@gmail.com', '03227200433', '$2y$10$CGIo3iA24FR7x5uGBvW1Sukkd0oEIIkvbogm49Qm6ubbdrN0c6sXK', 'customer', 'active', NULL, '2026-05-05 10:31:44', '2026-05-05 10:31:44'),
(15, 'Apple Official', 'apple@furqan.com', '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendor', 'active', NULL, '2026-05-05 11:00:42', '2026-05-05 11:00:42'),
(16, 'Sony World', 'sony@furqan.com', '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendor', 'active', NULL, '2026-05-05 11:00:42', '2026-05-05 11:00:42'),
(17, 'Adidas Original', 'adidas@furqan.com', '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendor', 'active', NULL, '2026-05-05 11:00:42', '2026-05-05 11:00:42'),
(18, 'Abdul wahab', 'abdulwahab23900', '03227200433', '$2y$10$w2tNtZD.2.ZRmpsS4R9AleTSvHcHO5.EcMtM4tj3KAefGa55vZOV2', 'customer', 'active', NULL, '2026-05-06 06:50:49', '2026-05-06 06:50:49'),
(19, 'Sehar', 'sehar@gmail.com', '03227200433', '$2y$10$YMjImkxplZbfdof4uO8GluI3WfpQacqutr20zf0fThble1n3aIrrW', 'customer', 'active', NULL, '2026-05-06 14:57:37', '2026-05-06 14:57:37'),
(20, 'Abdul wahab', 'abdulwahab23900@gmail.com', '03336596473', '$2y$10$5nuFjDLGKu4xmXrUewYr2u.r4WEnOdLf21dLWBbE9VNGbBpkt0hy6', 'customer', 'active', NULL, '2026-05-07 03:53:11', '2026-05-07 03:53:11');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_order_summary`
-- (See below for the actual view)
--
CREATE TABLE `v_order_summary` (
`order_id` int(11)
,`customer_name` varchar(100)
,`total` decimal(10,2)
,`status` enum('pending','processing','shipped','delivered','cancelled')
,`created_at` timestamp
,`total_items` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_vendor_performance`
-- (See below for the actual view)
--
CREATE TABLE `v_vendor_performance` (
`vendor_name` varchar(100)
,`total_units_sold` bigint(21)
,`total_earnings` decimal(32,2)
,`average_unit_price` decimal(14,6)
);

-- --------------------------------------------------------

--
-- Structure for view `v_order_summary`
--
DROP TABLE IF EXISTS `v_order_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_order_summary`  AS SELECT `o`.`id` AS `order_id`, `u`.`full_name` AS `customer_name`, `o`.`total` AS `total`, `o`.`status` AS `status`, `o`.`created_at` AS `created_at`, count(`oi`.`id`) AS `total_items` FROM ((`orders` `o` join `users` `u` on(`o`.`user_id` = `u`.`id`)) join `order_items` `oi` on(`o`.`id` = `oi`.`order_id`)) GROUP BY `o`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `v_vendor_performance`
--
DROP TABLE IF EXISTS `v_vendor_performance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_vendor_performance`  AS SELECT `u`.`full_name` AS `vendor_name`, count(`oi`.`id`) AS `total_units_sold`, sum(`oi`.`vendor_earning`) AS `total_earnings`, avg(`oi`.`price`) AS `average_unit_price` FROM (`users` `u` join `order_items` `oi` on(`u`.`id` = `oi`.`vendor_id`)) GROUP BY `u`.`id` HAVING `total_earnings` > 0 ORDER BY sum(`oi`.`vendor_earning`) DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
