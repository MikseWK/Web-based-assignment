-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2025 at 12:18 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `addon`
--

-- TABLE CREATION (in dependency order)

CREATE TABLE IF NOT EXISTS `addon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `availability` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `phoneNumber` text NOT NULL,
  `email` text NOT NULL,
  `role` varchar(50) DEFAULT 'Administrator',
  `profile_picture` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `phoneNumber` text NOT NULL,
  `email` text NOT NULL,
  `birthday` date NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `product` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `photo` VARCHAR(255) NULL,
  `category` VARCHAR(100) NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `cart` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_product` (`customer_id`, `product_id`),
  FOREIGN KEY (`customer_id`) REFERENCES `assignment`.`customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `assignment`.`product` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_name` varchar(50) NOT NULL,
  `discount_percentage` int(3) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `icecream_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `icecream_flavors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `icecream` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `flavor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `availability` char(1) NOT NULL DEFAULT 'Y',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `flavor_id` (`flavor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `payment_date` DATETIME NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` VARCHAR(255) NULL,
  `discount_id` INT NULL,
  `discount_amount` DECIMAL(10,2) DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `assignment`.`orders` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `discount_vouchers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `discount_type` ENUM('percentage', 'fixed') NOT NULL,
  `discount_value` DECIMAL(10,2) NOT NULL,
  `min_purchase` DECIMAL(10,2) DEFAULT 0,
  `valid_from` DATE NOT NULL,
  `valid_to` DATE NOT NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  `usage_limit` INT NULL,
  `usage_count` INT DEFAULT 0,
  PRIMARY KEY (`id`)
);


-- --------------------------------------------------------

--
-- Table structure for table `orderlist`
--

-- CREATE TABLE `orderlist` (
--   `id` int(11) NOT NULL,
--   `quantity` int(100) NOT NULL,
--   `addon` varchar(100) NOT NULL,
--   `specialinsturction` varchar(100) NOT NULL,
--   `subtotal` float NOT NULL,
--   `icecream_id` int(11) NOT NULL,
--   `addon_id` int(11) NOT NULL,
--   `serve_choice_id` int(11) NOT NULL,
--   `order_id` int(11) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderrecord`
--

-- CREATE TABLE `orderrecord` (
--   `id` int(11) NOT NULL,
--   `date` date NOT NULL,
--   `amount` float NOT NULL,
--   `customer_id` int(11) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

-- CREATE TABLE `payment` (
--   `id` int(11) NOT NULL,
--   `discount_amount` float NOT NULL,
--   `payment_method` varchar(50) NOT NULL,
--   `payment_date` date NOT NULL,
--   `order_id` int(11) NOT NULL,
--   `discount_id` int(11) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servechoice`
--

-- CREATE TABLE `servechoice` (
--   `id` int(11) NOT NULL,
--   `name` varchar(50) NOT NULL,
--   `additional_price` float NOT NULL,
--   `availability` varchar(1) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

-- CREATE TABLE `tag` (
--   `id` int(11) NOT NULL,
--   `name` varchar(50) NOT NULL,
--   `description` varchar(100) NOT NULL,
--   `availability` varchar(1) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taglist`
--

-- CREATE TABLE `taglist` (
--   `icecream_id` int(11) NOT NULL,
--   `tag_id` int(11) NOT NULL,
--   `is_active` varchar(1) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addon`
--
ALTER TABLE `addon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
-- Modify the admin table to include role and profile_picture fields
ALTER TABLE `admin` 
ADD `role` varchar(50) DEFAULT 'Administrator' AFTER `email`,
ADD `profile_picture` varchar(255) DEFAULT NULL AFTER `role`,
ADD `last_login` datetime DEFAULT NULL AFTER `profile_picture`;

-- Rename admin table to admins to match PHP references
RENAME TABLE `admin` TO `admins`;

-- Add session_id column to both customer and admins tables
ALTER TABLE `admins` 
ADD `session_id` varchar(255) DEFAULT NULL AFTER `last_login`;

ALTER TABLE `customer` 
ADD `profile_picture` varchar(255) DEFAULT NULL AFTER `email`,
ADD `session_id` varchar(255) DEFAULT NULL AFTER `profile_picture`;

-- Rename customer table to customers to match PHP references
RENAME TABLE `customer` TO `customers`;

-- Create products table for the menu items
-- CREATE TABLE `products` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `name` varchar(100) NOT NULL,
--   `description` text DEFAULT NULL,
--   `price` decimal(10,2) NOT NULL,
--   `image` varchar(255) DEFAULT NULL,
--   `category` varchar(50) DEFAULT NULL,
--   `availability` tinyint(1) DEFAULT 1,
--   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
--   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data from icecream_flavors into product table
INSERT INTO `product` (`name`, `description`, `price`, `photo`, `category`) VALUES
('Vanilla Ice Cream', 'Classic vanilla ice cream made with real vanilla beans', 4.99, 'vanilla.jpg', 'Regular'),
('Chocolate Ice Cream', 'Rich chocolate ice cream made with premium cocoa', 5.99, 'chocolate.jpg', 'Regular'),
('Strawberry Ice Cream', 'Creamy strawberry ice cream with real fruit pieces', 5.99, 'strawberry.jpg', 'Regular'),
('Mint Chocolate Chip', 'Refreshing mint ice cream with chocolate chips', 6.99, 'mint-choc.jpg', 'Premium'),
('Cookies and Cream', 'Vanilla ice cream with chocolate cookie pieces', 6.99, 'cookies-and-cream.jpg', 'Premium'),
('Butter Pecan', 'Buttery ice cream with pecan nuts', 5.99, 'butter-pecan.jpg', 'Specialty'),
('Coffee Ice Cream', 'Coffee-infused ice cream', 5.99, 'coffee.jpg', 'Specialty'),
('Rocky Road', 'Chocolate ice cream with marshmallows and nuts', 6.99, 'rocky-road.jpg', 'Premium'),
('Pistachio Ice Cream', 'Nutty pistachio flavored ice cream', 5.99, 'pistachio.jpg', 'Specialty'),
('Neapolitan Ice Cream', 'Combination of vanilla, chocolate, and strawberry', 5.99, 'neapolitan.jpg', 'Specialty');

-- Insert data from icecream_flavors into products table (if it exists)
-- First check if the products table exists

-- Insert sample product data into the product table (not products)
INSERT INTO `product` (`name`, `description`, `price`, `photo`, `category`) VALUES
('Vanilla Ice Cream', 'Classic vanilla ice cream made with real vanilla beans', 4.99, 'vanilla.jpg', 'Regular'),
('Chocolate Ice Cream', 'Rich chocolate ice cream made with premium cocoa', 5.99, 'chocolate.jpg', 'Regular'),
('Strawberry Ice Cream', 'Creamy strawberry ice cream with real fruit pieces', 5.99, 'strawberry.jpg', 'Regular'),
('Mint Chocolate Chip', 'Refreshing mint ice cream with chocolate chips', 6.99, 'mint-choc.jpg', 'Premium');

-- Create a contact_messages table for the contact form
-- CREATE TABLE `contact_messages` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `name` varchar(100) NOT NULL,
--   `email` varchar(100) NOT NULL,
--   `subject` varchar(200) NOT NULL,
--   `message` text NOT NULL,
--   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
--   `status` varchar(20) DEFAULT 'unread',
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add a status column to orderrecord table
ALTER TABLE `orderrecord` 
ADD `status` varchar(50) DEFAULT 'pending' AFTER `amount`,
ADD `created_at` timestamp NOT NULL DEFAULT current_timestamp() AFTER `status`;
--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `icecream`
--
ALTER TABLE `icecream`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_id` (`addon_id`),
  ADD KEY `icecream_id` (`icecream_id`),
  ADD KEY `serve_choice_id` (`serve_choice_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orderrecord`
--
ALTER TABLE `orderrecord`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `servechoice`
--
ALTER TABLE `servechoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taglist`
--
ALTER TABLE `taglist`
  ADD KEY `icecream_id` (`icecream_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addon`
--
ALTER TABLE `addon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `icecream`
--
ALTER TABLE `icecream`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orderlist`
--
ALTER TABLE `orderlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderrecord`
--
ALTER TABLE `orderrecord`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servechoice`
--
ALTER TABLE `servechoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `discount_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD CONSTRAINT `orderlist_ibfk_1` FOREIGN KEY (`addon_id`) REFERENCES `addon` (`id`),
  ADD CONSTRAINT `orderlist_ibfk_2` FOREIGN KEY (`icecream_id`) REFERENCES `icecream` (`id`),
  ADD CONSTRAINT `orderlist_ibfk_3` FOREIGN KEY (`serve_choice_id`) REFERENCES `servechoice` (`id`),
  ADD CONSTRAINT `orderlist_ibfk_4` FOREIGN KEY (`order_id`) REFERENCES `orderrecord` (`id`);

--
-- Constraints for table `orderrecord`
--
ALTER TABLE `orderrecord`
  ADD CONSTRAINT `orderrecord_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orderrecord` (`id`);

--
-- Constraints for table `taglist`
--
ALTER TABLE `taglist`
  ADD CONSTRAINT `taglist_ibfk_1` FOREIGN KEY (`icecream_id`) REFERENCES `icecream` (`id`),
  ADD CONSTRAINT `taglist_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Update the password for the admin with email A@gmail.com to use SHA1
UPDATE `admin` 
SET `password` = SHA1('123456') 
WHERE `email` = 'A@gmail.com';

-- Create a dashboard_stats table to store aggregated statistics
CREATE TABLE `dashboard_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stat_date` date NOT NULL,
  `new_customers` int(11) DEFAULT 0,
  `total_sales` decimal(10,2) DEFAULT 0.00,
  `new_orders` int(11) DEFAULT 0,
  `total_products` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `stat_date` (`stat_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create a product_categories table
CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default product categories
INSERT INTO `product_categories` (`name`, `description`) VALUES
('Ice Cream', 'All ice cream products'),
('Cakes', 'All cake products'),
('Beverages', 'All drink products'),
('Toppings', 'All topping products');

-- Modify products table to include category_id
ALTER TABLE `products` 
ADD `category_id` int(11) DEFAULT NULL AFTER `category`,
ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`);

-- Create a monthly_sales table for statistics
CREATE TABLE `monthly_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `total_sales` decimal(10,2) DEFAULT 0.00,
  `units_sold` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `month_year_product` (`month`, `year`, `product_id`),
  KEY `fk_monthly_sales_product` (`product_id`),
  KEY `fk_monthly_sales_category` (`category_id`),
  CONSTRAINT `fk_monthly_sales_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `fk_monthly_sales_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create a customer_registration_log table to track new customer registrations
CREATE TABLE `customer_registration_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `registration_date` date NOT NULL,
  `source` varchar(50) DEFAULT 'website',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_customer_reg_customer` (`customer_id`),
  CONSTRAINT `fk_customer_reg_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create a orders table to track all orders
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_orders_customer` (`customer_id`),
  CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create a order_items table to track items in each order
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_order_items_order` (`order_id`),
  KEY `fk_order_items_product` (`product_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data for dashboard testing
-- Insert sample ice cream records
INSERT INTO `icecream` (`type_id`, `flavor_id`, `name`, `price`, `availability`) VALUES
(1, 1, 'Classic Vanilla', 4.99, 'Y'),
(1, 2, 'Classic Chocolate', 4.99, 'Y'),
(1, 3, 'Classic Strawberry', 4.99, 'Y'),
(2, 4, 'Premium Mint Chocolate Chip', 6.99, 'Y'),
(2, 5, 'Premium Cookies and Cream', 6.99, 'Y'),
(3, 1, 'Low-fat Vanilla', 5.49, 'Y'),
(4, 7, 'Dairy-free Coffee', 7.99, 'Y'),
(5, 3, 'Strawberry Sorbet', 5.99, 'Y'),
(2, 8, 'Premium Rocky Road', 7.49, 'Y'),
(1, 9, 'Classic Pistachio', 5.99, 'Y');

-- Insert additional sample data for dashboard_stats
INSERT INTO `dashboard_stats` (`stat_date`, `new_customers`, `total_sales`, `new_orders`, `total_products`) VALUES
(DATE_SUB(CURDATE(), INTERVAL 3 DAY), 4, 1125.50, 10, 24),
(DATE_SUB(CURDATE(), INTERVAL 4 DAY), 6, 1350.25, 13, 24),
(DATE_SUB(CURDATE(), INTERVAL 5 DAY), 2, 950.75, 7, 23),
(DATE_SUB(CURDATE(), INTERVAL 6 DAY), 5, 1425.00, 11, 23)
ON DUPLICATE KEY UPDATE 
    `new_customers` = VALUES(`new_customers`),
    `total_sales` = VALUES(`total_sales`),
    `new_orders` = VALUES(`new_orders`),
    `total_products` = VALUES(`total_products`),
    `updated_at` = CURRENT_TIMESTAMP();

-- Insert additional monthly sales data for better charts
  INSERT INTO `monthly_sales` (`month`, `year`, `product_id`, `category_id`, `total_sales`, `units_sold`) VALUES
  (MONTH(CURDATE()), YEAR(CURDATE()), 1, 1, 2500.75, 500),
  (MONTH(CURDATE()), YEAR(CURDATE()), 2, 1, 1800.50, 300),
  (MONTH(CURDATE()), YEAR(CURDATE()), 3, 1, 1200.25, 200),
  (MONTH(CURDATE()), YEAR(CURDATE()), 4, 1, 3000.00, 400),
  (MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), 1, 1, 2200.50, 450),
  (MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), 2, 1, 1650.75, 275),
  (MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), 3, 1, 1100.00, 180),
  (MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), 4, 1, 2800.25, 375);

-- Insert sample data for category sales (for pie chart)
INSERT INTO `monthly_sales` (`month`, `year`, `product_id`, `category_id`, `total_sales`, `units_sold`) VALUES
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 1, 8500.50, 1400),
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 2, 5200.75, 650),
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 3, 2100.25, 420),
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 4, 1500.00, 300);

-- Insert sample customers for the dashboard
INSERT INTO `customers` (`name`, `password`, `phoneNumber`, `email`, `birthday`) VALUES
('John Smith', SHA1('password123'), '0123456789', 'john@example.com', '1990-05-15'),
('Jane Doe', SHA1('password123'), '0123456788', 'jane@example.com', '1992-08-22'),
('Mike Johnson', SHA1('password123'), '0123456787', 'mike@example.com', '1985-11-10'),
('Sarah Williams', SHA1('password123'), '0123456786', 'sarah@example.com', '1995-03-28'),
('David Brown', SHA1('password123'), '0123456785', 'david@example.com', '1988-07-14');

-- Insert sample customer registration logs
INSERT INTO `customer_registration_log` (`customer_id`, `registration_date`, `source`) VALUES
(1, CURDATE(), 'website'),
(2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'mobile_app'),
(3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'website'),
(4, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'website'),
(5, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'mobile_app');

-- Insert sample orders for the dashboard
INSERT INTO `orders` (`customer_id`, `order_date`, `total_amount`, `status`, `payment_method`) VALUES
(1, NOW(), 25.99, 'completed', 'credit_card'),
(2, DATE_SUB(NOW(), INTERVAL 1 HOUR), 18.50, 'completed', 'credit_card'),
(3, DATE_SUB(NOW(), INTERVAL 3 HOUR), 32.75, 'processing', 'paypal'),
(4, DATE_SUB(NOW(), INTERVAL 5 HOUR), 15.25, 'completed', 'credit_card'),
(1, DATE_SUB(NOW(), INTERVAL 1 DAY), 22.50, 'completed', 'credit_card'),
(2, DATE_SUB(NOW(), INTERVAL 1 DAY), 19.99, 'completed', 'paypal'),
(3, DATE_SUB(NOW(), INTERVAL 2 DAY), 28.75, 'completed', 'credit_card'),
(5, DATE_SUB(NOW(), INTERVAL 2 DAY), 35.50, 'completed', 'credit_card'),
(4, DATE_SUB(NOW(), INTERVAL 3 DAY), 12.99, 'completed', 'paypal'),
(5, DATE_SUB(NOW(), INTERVAL 4 DAY), 24.50, 'completed', 'credit_card');

-- Insert sample order items
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 2, 4.99, 9.98),
(1, 3, 1, 5.99, 5.99),
(1, 4, 1, 6.99, 6.99),
(2, 2, 2, 5.99, 11.98),
(2, 4, 1, 6.99, 6.99),
(3, 1, 1, 4.99, 4.99),
(3, 2, 2, 5.99, 11.98),
(3, 3, 1, 5.99, 5.99),
(3, 4, 1, 6.99, 6.99),
(4, 1, 3, 4.99, 14.97),
(5, 2, 2, 5.99, 11.98),
(5, 3, 1, 5.99, 5.99),
(6, 4, 2, 6.99, 13.98),
(7, 1, 1, 4.99, 4.99),
(7, 2, 2, 5.99, 11.98),
(7, 3, 1, 5.99, 5.99),
(8, 3, 3, 5.99, 17.97),
(8, 4, 2, 6.99, 13.98),
(9, 1, 2, 4.99, 9.98),
(10, 2, 2, 5.99, 11.98),
(10, 4, 1, 6.99, 6.99);

-- Create a stored procedure to update dashboard stats daily
DELIMITER //
CREATE PROCEDURE update_dashboard_stats()
BEGIN
  DECLARE today DATE;
  DECLARE new_customers_count INT;
  DECLARE total_sales_amount DECIMAL(10,2);
  DECLARE new_orders_count INT;
  DECLARE total_products_count INT;

  SET today = CURDATE();
  
  -- Count new customers registered today
  SELECT COUNT(*) INTO new_customers_count 
  FROM customer_registration_log 
  WHERE registration_date = today;
  
  -- Calculate total sales for today
  SELECT COALESCE(SUM(total_amount), 0) INTO total_sales_amount 
  FROM orders 
  WHERE DATE(order_date) = today;
  
  -- Count new orders placed today
  SELECT COUNT(*) INTO new_orders_count 
  FROM orders 
  WHERE DATE(order_date) = today;
  
  -- Count total active products
  SELECT COUNT(*) INTO total_products_count 
  FROM products 
  WHERE availability = 1;
  
  -- Insert or update dashboard stats
  INSERT INTO dashboard_stats 
    (stat_date, new_customers, total_sales, new_orders, total_products)
  VALUES 
    (today, new_customers_count, total_sales_amount, new_orders_count, total_products_count)
  ON DUPLICATE KEY UPDATE
    new_customers = new_customers_count,
    total_sales = total_sales_amount,
    new_orders = new_orders_count,
    total_products = total_products_count,
    updated_at = NOW();
END //
DELIMITER ;

INSERT INTO `icecream_types` (`name`, `description`) VALUES
('Regular', 'Standard ice cream with regular fat content'),
('Premium', 'Rich and creamy ice cream with higher fat content'),
('Low-fat', 'Ice cream with reduced fat content'),
('Dairy-free', 'Ice cream made without dairy products'),
('Sorbet', 'Fruit-based frozen dessert without dairy');

INSERT INTO `icecream_flavors` (`name`, `description`) VALUES
('Vanilla', 'Classic vanilla flavor'),
('Chocolate', 'Rich chocolate flavor'),
('Strawberry', 'Sweet strawberry flavor'),
('Mint Chocolate Chip', 'Refreshing mint with chocolate chips'),
('Cookies and Cream', 'Vanilla with cookie pieces'),
('Butter Pecan', 'Buttery flavor with pecan nuts'),
('Coffee', 'Coffee-infused ice cream'),
('Rocky Road', 'Chocolate with marshmallows and nuts'),
('Pistachio', 'Nutty pistachio flavor'),
('Neapolitan', 'Combination of vanilla, chocolate, and strawberry');

INSERT INTO `icecream` (`type_id`, `flavor_id`, `name`, `price`, `availability`) VALUES
(1, 1, 'Classic Vanilla', 4.99, 'Y'),
(1, 2, 'Classic Chocolate', 4.99, 'Y'),
(1, 3, 'Classic Strawberry', 4.99, 'Y'),
(2, 4, 'Premium Mint Chocolate Chip', 6.99, 'Y'),
(2, 5, 'Premium Cookies and Cream', 6.99, 'Y'),
(3, 1, 'Low-fat Vanilla', 5.49, 'Y'),
(4, 7, 'Dairy-free Coffee', 7.99, 'Y'),
(5, 3, 'Strawberry Sorbet', 5.99, 'Y'),
(2, 8, 'Premium Rocky Road', 7.49, 'Y'),
(1, 9, 'Classic Pistachio', 5.99, 'Y');

-- Insert additional sample data for dashboard_stats
INSERT INTO `dashboard_stats` (`stat_date`, `new_customers`, `total_sales`, `new_orders`, `total_products`) VALUES
(DATE_SUB(CURDATE(), INTERVAL 3 DAY), 4, 1125.50, 10, 24),
(DATE_SUB(CURDATE(), INTERVAL 4 DAY), 6, 1350.25, 13, 24),
(DATE_SUB(CURDATE(), INTERVAL 5 DAY), 2, 950.75, 7, 23),
(DATE_SUB(CURDATE(), INTERVAL 6 DAY), 5, 1425.00, 11, 23)
ON DUPLICATE KEY UPDATE 
    `new_customers` = VALUES(`new_customers`),
    `total_sales` = VALUES(`total_sales`),
    `new_orders` = VALUES(`new_orders`),
    `total_products` = VALUES(`total_products`),
    `updated_at` = CURRENT_TIMESTAMP();

-- Insert additional monthly sales data for better charts
  INSERT INTO `monthly_sales` (`month`, `year`, `product_id`, `category_id`, `total_sales`, `units_sold`) VALUES
  (MONTH(CURDATE()), YEAR(CURDATE()), 1, 1, 2500.75, 500),
  (MONTH(CURDATE()), YEAR(CURDATE()), 2, 1, 1800.50, 300),
  (MONTH(CURDATE()), YEAR(CURDATE()), 3, 1, 1200.25, 200),
  (MONTH(CURDATE()), YEAR(CURDATE()), 4, 1, 3000.00, 400),
  (MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), 1, 1, 2200.50, 450),
  (MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), 2, 1, 1650.75, 275),
  (MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), 3, 1, 1100.00, 180),
  (MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)), 4, 1, 2800.25, 375);

-- Insert sample data for category sales (for pie chart)
INSERT INTO `monthly_sales` (`month`, `year`, `product_id`, `category_id`, `total_sales`, `units_sold`) VALUES
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 1, 8500.50, 1400),
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 2, 5200.75, 650),
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 3, 2100.25, 420),
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 4, 1500.00, 300);

-- Insert sample customers for the dashboard
INSERT INTO `customers` (`name`, `password`, `phoneNumber`, `email`, `birthday`) VALUES
('John Smith', SHA1('password123'), '0123456789', 'john@example.com', '1990-05-15'),
('Jane Doe', SHA1('password123'), '0123456788', 'jane@example.com', '1992-08-22'),
('Mike Johnson', SHA1('password123'), '0123456787', 'mike@example.com', '1985-11-10'),
('Sarah Williams', SHA1('password123'), '0123456786', 'sarah@example.com', '1995-03-28'),
('David Brown', SHA1('password123'), '0123456785', 'david@example.com', '1988-07-14');

-- Insert sample customer registration logs
INSERT INTO `customer_registration_log` (`customer_id`, `registration_date`, `source`) VALUES
(1, CURDATE(), 'website'),
(2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'mobile_app'),
(3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'website'),
(4, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'website'),
(5, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'mobile_app');

-- Insert sample orders for the dashboard
INSERT INTO `orders` (`customer_id`, `order_date`, `total_amount`, `status`, `payment_method`) VALUES
(1, NOW(), 25.99, 'completed', 'credit_card'),
(2, DATE_SUB(NOW(), INTERVAL 1 HOUR), 18.50, 'completed', 'credit_card'),
(3, DATE_SUB(NOW(), INTERVAL 3 HOUR), 32.75, 'processing', 'paypal'),
(4, DATE_SUB(NOW(), INTERVAL 5 HOUR), 15.25, 'completed', 'credit_card'),
(1, DATE_SUB(NOW(), INTERVAL 1 DAY), 22.50, 'completed', 'credit_card'),
(2, DATE_SUB(NOW(), INTERVAL 1 DAY), 19.99, 'completed', 'paypal'),
(3, DATE_SUB(NOW(), INTERVAL 2 DAY), 28.75, 'completed', 'credit_card'),
(5, DATE_SUB(NOW(), INTERVAL 2 DAY), 35.50, 'completed', 'credit_card'),
(4, DATE_SUB(NOW(), INTERVAL 3 DAY), 12.99, 'completed', 'paypal'),
(5, DATE_SUB(NOW(), INTERVAL 4 DAY), 24.50, 'completed', 'credit_card');

-- Insert sample order items
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 2, 4.99, 9.98),
(1, 3, 1, 5.99, 5.99),
(1, 4, 1, 6.99, 6.99),
(2, 2, 2, 5.99, 11.98),
(2, 4, 1, 6.99, 6.99),
(3, 1, 1, 4.99, 4.99),
(3, 2, 2, 5.99, 11.98),
(3, 3, 1, 5.99, 5.99),
(3, 4, 1, 6.99, 6.99),
(4, 1, 3, 4.99, 14.97),
(5, 2, 2, 5.99, 11.98),
(5, 3, 1, 5.99, 5.99),
(6, 4, 2, 6.99, 13.98),
(7, 1, 1, 4.99, 4.99),
(7, 2, 2, 5.99, 11.98),
(7, 3, 1, 5.99, 5.99),
(8, 3, 3, 5.99, 17.97),
(8, 4, 2, 6.99, 13.98),
(9, 1, 2, 4.99, 9.98),
(10, 2, 2, 5.99, 11.98),
(10, 4, 1, 6.99, 6.99);

-- Create a stored procedure to update dashboard stats daily
DELIMITER //
CREATE PROCEDURE update_dashboard_stats()
BEGIN
  DECLARE today DATE;
  DECLARE new_customers_count INT;
  DECLARE total_sales_amount DECIMAL(10,2);
  DECLARE new_orders_count INT;
  DECLARE total_products_count INT;

  SET today = CURDATE();

  SELECT COUNT(*) INTO new_customers_count 
  FROM customer_registration_log 
  WHERE registration_date = today;

  SELECT COALESCE(SUM(total_amount), 0) INTO total_sales_amount 
  FROM orders 
  WHERE DATE(order_date) = today;

  SELECT COUNT(*) INTO new_orders_count 
  FROM orders 
  WHERE DATE(order_date) = today;

  SELECT COUNT(*) INTO total_products_count 
  FROM products 
  WHERE availability = 1;

  INSERT INTO dashboard_stats 
    (stat_date, new_customers, total_sales, new_orders, total_products)
  VALUES 
    (today, new_customers_count, total_sales_amount, new_orders_count, total_products_count)
  ON DUPLICATE KEY UPDATE
    new_customers = new_customers_count,
    total_sales = total_sales_amount,
    new_orders = new_orders_count,
    total_products = total_products_count,
    updated_at = NOW();
END //
DELIMITER ;

COMMIT;
