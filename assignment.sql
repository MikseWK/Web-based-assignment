-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 03:59 PM
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
-- Database: `assignment`
--
CREATE DATABASE IF NOT EXISTS `assignment` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `assignment`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `phoneNumber` text NOT NULL,
  `email` text NOT NULL,
  `role` varchar(50) DEFAULT 'Administrator',
  `profile_picture` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` char(4) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `gender` varchar(1) NOT NULL,
  `password` text NOT NULL,
  `phoneNumber` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_picture` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token` varchar(64) NOT NULL,
  `token_expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `gender`, `password`, `phoneNumber`, `email`, `profile_picture`, `birthday`, `email_verified`, `verification_token`, `token_expires`) VALUES
(1, 'haha', '', '1c7f4e266fefa295c5ae90317aaa0e7c6931e7d7', '0154154151', 'liewchoonfei22@gmail.com', '', '0000-00-00', 1, '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_stats`
--

CREATE TABLE `dashboard_stats` (
  `id` int(11) NOT NULL,
  `stat_date` date NOT NULL,
  `new_customers` int(11) DEFAULT 0,
  `total_sales` decimal(10,2) DEFAULT 0.00,
  `new_orders` int(11) DEFAULT 0,
  `total_products` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_sales`
--

CREATE TABLE `monthly_sales` (
  `id` int(11) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `product_id` char(4) DEFAULT NULL,
  `total_sales` decimal(10,2) DEFAULT 0.00,
  `units_sold` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `order_id` int(11) NOT NULL,
  `product_id` char(4) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Success','Fail','Pending','Expired') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `email` varchar(255) NOT NULL,
  `payment_status` enum('Success','Fail') NOT NULL DEFAULT 'Fail',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` char(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `Flavour` varchar(20) NOT NULL,
  `price` decimal(4,2) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `Description` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `availability` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

CREATE TABLE `customer_registration_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `registration_date` date NOT NULL,
  `source` varchar(50) DEFAULT 'website',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_registration_customer` (`customer_id`),
  CONSTRAINT `fk_registration_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `product` (`id`, `name`, `category`, `Flavour`, `price`, `photo`, `Description`, `quantity`, `availability`) VALUES
('P001', 'Classic Chocolate', 'Classic', 'Chocolate', 5.99, 'Classic Chocolate.jpg', 'Deep, cocoa-forward flavor made with Dutch-processed chocolate for a bittersweet richness.\'', 100, 0),
('P002', 'Classic Vanilla', 'Classic', 'Vanilla', 5.99, 'classicvanilla.jpg', 'A timeless classic made with Madagascar vanilla beans, offering a creamy, floral sweetness with tiny black speckles of real vanilla.', 0, 0),
('P003', 'Classic Strawberry', 'Classic', 'Strawberry', 5.99, 'classicstrawberry.jpg', 'Made with ripe strawberries for a bright, fruity sweetness balanced by creamy dairy.', 0, 0),
('P004', 'Classic Mint Chocolate', 'Classic', 'Chocolate', 6.99, 'classicmintchocolatechip.jpg', 'Cool peppermint ice cream studded with dark chocolate shavings and crunchy chocolate bits topping.', 0, 0),
('P005', 'Classic Cookies N Cream', 'Classic', 'Cookies', 6.99, 'classiccookiesncream.jpg', 'Vanilla base packed with crushed Oreo® cookies for a crunchy, chocolaty contrast.', 0, 0),
('P006', 'Classic Coffee', 'Classic', 'Coffee', 6.99, 'classiccoffee.jpg', 'Made with cold-brewed espresso or dark roast coffee for a bold, aromatic kick.', 0, 0),
('P007', 'Gelato Chocolate', 'Gelato', 'Chocolate', 9.99, 'gelatochocolate.jpg', 'Made with high-quality cocoa or melted chocolate, it has a velvety texture and a deep, indulgent flavor.', 0, 0),
('P008', 'Gelato Salted Caramel', 'Gelato', 'Caramel', 10.99, 'gelatosaltedcaramel.jpg', 'Buttery caramel gelato with a hint of sea salt.', 0, 0),
('P009', 'Gelato Pistachio', 'Gelato', 'Pistachio', 12.99, 'gelatopistachio.jpg', 'Made with Sicilian pistachios, giving it a nutty, slightly salty depth.\r\n\r\n', 0, 0),
('P010', 'Gelato Hazelnut', 'Gelato', 'Hazelnut', 10.99, 'gelatohazelnut.jpg', 'Roasted Piedmont hazelnuts blended into a Nutella-like richness.', 0, 0),
('P011', 'Gelato Coffee', 'Gelato', 'Coffee', 11.99, 'gelatocoffee.jpg', 'Smooth and velvety, this Italian-style coffee gelato blends rich espresso or strong coffee into a creamy base.', 0, 0),
('P012', 'Matcha Ice Cream Cake', 'Cake', 'Matcha', 19.99, 'cakematcha.jpg', 'A dreamy dessert combining layers of creamy matcha green tea ice cream with a buttery biscuit base.', 0, 0),
('P013', 'Chocolate Ice Cream Cake', 'Cake', 'Chocolate', 17.99, 'cakechocolate.jpg', 'A decadent dessert with layers of rich, creamy chocolate ice cream and moist chocolate cake.', 0, 0),
('P014', 'Mixed Berries Ice Cream Cake', 'Cake', 'Fruits', 18.99, 'cakeberries.jpg', 'A refreshing, fruity delight with layers of creamy vanilla or berry ice cream, swirls of mixed berries.', 0, 0),
('P015', 'Yogurt Vanilla', 'Yogurt', 'Vanilla', 12.99, 'yogurtvanilla.jpg', 'Creamy, tangy, and lightly sweet, this vanilla ice cream is made with rich yogurt for a refreshing twist.', 0, 0),
('P016', 'Yogurt Strawberry', 'Yogurt', 'Strawberry', 11.99, 'yogurtstrawberry.jpg', 'Creamy and bursting with fresh strawberry flavor, this frozen treat blends rich yogurt with sweet strawberry purée or swirls.', 0, 0),
('P017', 'Yogurt Matcha', 'Yogurt', 'Matcha', 14.99, 'yogurtmatcha.jpg', 'A creamy, tangy-sweet fusion of rich yogurt and vibrant matcha green tea.', 0, 0),
('P018', 'Yogurt Chocolate', 'Yogurt', 'Chocolate', 12.99, 'yogurtchocolate.jpg', 'This creamy treat blends deep cocoa or melted chocolate into luscious yogurt for a lighter yet decadent dessert.', 0, 0),
('P019', 'Peach Sorbet', 'Sorbet', 'Fruits', 7.99, 'sorbetpeach.jpg', 'A vibrant, dairy-free delight bursting with juicy, sun-ripened peaches.', 0, 0),
('P020', 'Mango Sorbet', 'Sorbet', 'Fruits', 7.99, 'sorbetmango.jpg', 'This luscious, dairy-free sorbet blends ripe mango purée with a splash of lime for a tropical, silky-smooth treat.', 0, 0),
('P021', 'Lemon Sorbet', 'Sorbet', 'Fruits', 7.99, 'sorbetlemon.jpg', 'This dairy-free sorbet combines tangy lemon juice with a touch of honey sweetness, creating a silky taste.', 0, 0),
('P022', 'Guava Sorbet', 'Sorbet', 'Fruits', 7.99, 'sorbetguava.jpg', 'Tropical, vibrant, and bursting with floral-sweet guava purée, this dairy-free sorbet is pure sunshine in every scoop.', 0, 0),
('P023', 'Coconut Sorbet', 'Sorbet', 'Fruits', 7.99, 'sorbetcoconut.jpg', 'This dairy-free sorbet blends rich coconut milk with a hint of vanilla or lime for a silky, exotic treat.', 0, 0),
('P024', 'Vanilla Soft Serve', 'Soft Serve', 'Vanilla', 4.99, 'softservevanilla.jpg', 'This classic soft serve swirls sweet vanilla bean goodness into a light, velvety texture that melts perfectly on your tongue.', 0, 0),
('P025', 'Matcha Soft Serve', 'Soft Serve', 'Matcha', 4.99, 'softservematcha.jpg', 'This Japanese-inspired soft serve swirls premium matcha green tea into a creamy, subtly sweet base.', 0, 0),
('P026', 'Chocolate Soft Serve', 'Soft Serve', 'Chocolate', 4.99, 'softservechocolate.png', 'This decadent soft serve swirls deep cocoa or melted chocolate into a creamy, dreamy texture.', 0, 0),
('P027', 'Caramel Soft Serve', 'Soft Serve', 'Caramel', 4.99, 'softservecaramel.jpg', 'This golden soft serve swirls rich caramel into a velvety base, with a hint of sea salt for that perfect sweet-salty balance.', 0, 0),
('P069', 'JackMa', 'Person', 'Cum', 99.99, 'JackMa.jpg', 'Jack Ma big big', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `dashboard_stats`
--
ALTER TABLE `dashboard_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stat_date` (`stat_date`);

--
-- Indexes for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `month_year_product` (`month`,`year`,`product_id`),
  ADD KEY `fk_monthly_sales_product` (`product_id`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `orderitem_ibfk_2` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_ibfk_1` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_order` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dashboard_stats`
--
ALTER TABLE `dashboard_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  ADD CONSTRAINT `fk_monthly_sales_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_password_resets_customer` FOREIGN KEY (`email`) REFERENCES `customer` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;


DELIMITER //

CREATE PROCEDURE update_dashboard_stats()
BEGIN
  DECLARE today DATE;
  DECLARE new_customer_count INT;
  DECLARE total_sales_amount DECIMAL(10,2);
  DECLARE new_orders_count INT;
  DECLARE total_products_count INT;

  SET today = CURDATE();

  SELECT COUNT(*) INTO new_customer_count 
  FROM customer_registration_log 
  WHERE registration_date = today;

  SELECT COALESCE(SUM(total_amount), 0) INTO total_sales_amount 
  FROM orders 
  WHERE DATE(order_date) = today;

  SELECT COUNT(*) INTO new_orders_count 
  FROM orders 
  WHERE DATE(order_date) = today;

  SELECT COUNT(*) INTO total_products_count 
  FROM product 
  WHERE availability = 1;

  INSERT INTO dashboard_stats 
    (stat_date, new_customer, total_sales, new_orders, total_products)
  VALUES 
    (today, new_customer_count, total_sales_amount, new_orders_count, total_products_count)
  ON DUPLICATE KEY UPDATE
    new_customer = new_customer_count,
    total_sales = total_sales_amount,
    new_orders = new_orders_count,
    total_products = total_products_count,
    updated_at = NOW();
END //

DELIMITER ;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
