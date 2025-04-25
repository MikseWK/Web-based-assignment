-- Character set setup
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `assignment`

-- 1. Base tables (no FKs)
CREATE TABLE IF NOT EXISTS `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
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

CREATE TABLE IF NOT EXISTS `addon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `availability` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `admin` (
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

CREATE TABLE IF NOT EXISTS `customer` (
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

CREATE TABLE IF NOT EXISTS `servechoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `additional_price` float NOT NULL,
  `availability` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `availability` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Tables with simple FKs to base tables
CREATE TABLE IF NOT EXISTS `product` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `photo` VARCHAR(255) NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `availability` TINYINT(1) DEFAULT 1,
  `category_id` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`)
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
  KEY `flavor_id` (`flavor_id`),
  CONSTRAINT `icecream_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `icecream_types` (`id`),
  CONSTRAINT `icecream_ibfk_2` FOREIGN KEY (`flavor_id`) REFERENCES `icecream_flavors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_name` varchar(50) NOT NULL,
  `discount_percentage` int(3) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `discount_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `customer_registration_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `registration_date` date NOT NULL,
  `source` varchar(50) DEFAULT 'website',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_customer_reg_customer` (`customer_id`),
  CONSTRAINT `fk_customer_reg_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `taglist` (
  `icecream_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `is_active` varchar(1) NOT NULL,
  PRIMARY KEY (`icecream_id`, `tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `taglist_ibfk_1` FOREIGN KEY (`icecream_id`) REFERENCES `icecream` (`id`),
  CONSTRAINT `taglist_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Tables with complex FKs or dependent on above tables
CREATE TABLE `orders` ( 
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL, 
  `count` int(11) NOT NULL DEFAULT 0, 
  `total` decimal(10,2) NOT NULL DEFAULT 0.00, 
  `datetime` datetime NOT NULL DEFAULT current_timestamp(), 
  `status` enum('Success','Fail','Pending','Expired') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=22; 

INSERT INTO `orders` (`id`, `user_id`, `count`, `total`, `datetime`, `status`) VALUES 
(21, 4, 6, 38.94, '2025-04-23 10:37:00', 'Success'); 

-- CREATE TABLE IF NOT EXISTS `orderrecord` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `date` date NOT NULL,
--   `amount` float NOT NULL,
--   `customer_id` int(11) NOT NULL,
--   `status` varchar(50) DEFAULT 'pending',
--   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
--   PRIMARY KEY (`id`),
--   KEY `customer_id` (`customer_id`),
--   CONSTRAINT `orderrecord_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `payment_date` DATETIME NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` VARCHAR(255) NULL,
  `discount_id` INT(11) NULL,
  `discount_amount` DECIMAL(10,2) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `discount_id` (`discount_id`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `orderitem` ( 
  `order_id` int(11) NOT NULL, 
  `product_id` char(4) NOT NULL, 
  `quantity` int(11) NOT NULL, 
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `orderitem` (`order_id`, `product_id`, `quantity`, `subtotal`) VALUES 
(21, 'P001', 2, 11.98), 
(21, 'P002', 1, 5.99), 
(21, 'P004', 1, 6.99), 
(21, 'P005', 2, 13.98);
CREATE TABLE IF NOT EXISTS `orderlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(100) NOT NULL,
  `addon` varchar(100) NOT NULL,
  `specialinsturction` varchar(100) NOT NULL,
  `subtotal` float NOT NULL,
  `icecream_id` int(11) NOT NULL,
  `addon_id` int(11) NOT NULL,
  `serve_choice_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `addon_id` (`addon_id`),
  KEY `icecream_id` (`icecream_id`),
  KEY `serve_choice_id` (`serve_choice_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `orderlist_ibfk_1` FOREIGN KEY (`addon_id`) REFERENCES `addon` (`id`),
  CONSTRAINT `orderlist_ibfk_2` FOREIGN KEY (`icecream_id`) REFERENCES `icecream` (`id`),
  CONSTRAINT `orderlist_ibfk_3` FOREIGN KEY (`serve_choice_id`) REFERENCES `servechoice` (`id`),
  CONSTRAINT `orderlist_ibfk_4` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cart` ( 
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL, 
  `product_id` char(4) NOT NULL, 
  `quantity` int(11) NOT NULL DEFAULT 1, 
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), 
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_product` (`user_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=115; 

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES 
(48, 12, 'P003', 9, '2025-04-15 18:46:21', '2025-04-15 20:12:46'), 
(49, 12, 'P002', 11, '2025-04-15 18:46:22', '2025-04-15 20:12:47'), 
(51, 12, 'P005', 5, '2025-04-15 18:46:49', '2025-04-15 20:12:49'), 
(52, 12, 'P001', 8, '2025-04-15 18:53:31', '2025-04-15 20:12:48'), 
(53, 12, 'P004', 1, '2025-04-15 20:12:49', '2025-04-15 20:12:49'), 
(54, 12, 'P008', 1, '2025-04-15 20:12:51', '2025-04-15 20:12:51'), 
(55, 12, 'P007', 1, '2025-04-15 20:12:52', '2025-04-15 20:12:52'), 
(107, 11, 'P002', 9, '2025-04-20 18:48:20', '2025-04-20 19:44:20'), 
(108, 11, 'P001', 3, '2025-04-20 18:48:29', '2025-04-20 19:44:23'), 
(109, 11, 'P019', 1, '2025-04-20 18:48:39', '2025-04-20 18:48:39'), 
(110, 11, 'P018', 1, '2025-04-20 18:48:59', '2025-04-20 18:48:59'), 
(111, 11, 'P016', 1, '2025-04-20 19:33:02', '2025-04-20 19:33:02'), 
(112, 11, 'P015', 1, '2025-04-20 19:33:06', '2025-04-20 19:33:06'), 
(113, 11, 'P003', 1, '2025-04-20 19:44:21', '2025-04-20 19:44:21'), 
(114, 11, 'P004', 1, '2025-04-20 19:44:25', '2025-04-20 19:44:25'); 


CREATE TABLE IF NOT EXISTS `monthly_sales` (
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
  CONSTRAINT `fk_monthly_sales_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `fk_monthly_sales_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Tables with no foreign keys (independent tables)
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'unread',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `dashboard_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stat_date` date NOT NULL,
  `new_customer` int(11) DEFAULT 0,
  `total_sales` decimal(10,2) DEFAULT 0.00,
  `new_orders` int(11) DEFAULT 0,
  `total_products` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `stat_date` (`stat_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. Insert data into tables (in correct order)
-- First insert into base tables with no dependencies
INSERT INTO `product_categories` (`name`, `description`) VALUES
('Ice Cream', 'All ice cream products'),
('Cakes', 'All cake products'),
('Beverages', 'All drink products'),
('Toppings', 'All topping products');

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

INSERT INTO `customer` (`name`, `password`, `phoneNumber`, `email`, `birthday`) VALUES
('John Smith', SHA1('password123'), '0123456789', 'john@example.com', '1990-05-15'),
('Jane Doe', SHA1('password123'), '0123456788', 'jane@example.com', '1992-08-22'),
('Mike Johnson', SHA1('password123'), '0123456787', 'mike@example.com', '1985-11-10'),
('Sarah Williams', SHA1('password123'), '0123456786', 'sarah@example.com', '1995-03-28'),
('David Brown', SHA1('password123'), '0123456785', 'david@example.com', '1988-07-14');

-- Then insert into tables that depend on the base tables
INSERT INTO `product` (`name`, `description`, `price`, `photo`, `category_id`) VALUES
('Vanilla Ice Cream', 'Classic vanilla ice cream made with real vanilla beans', 4.99, 'vanilla.jpg', 1),
('Chocolate Ice Cream', 'Rich chocolate ice cream made with premium cocoa', 5.99, 'chocolate.jpg', 1),
('Strawberry Ice Cream', 'Creamy strawberry ice cream with real fruit pieces', 5.99, 'strawberry.jpg', 1),
('Mint Chocolate Chip', 'Refreshing mint ice cream with chocolate chips', 6.99, 'mint-choc.jpg', 1),
('Cookies and Cream', 'Vanilla ice cream with chocolate cookie pieces', 6.99, 'cookies-and-cream.jpg', 1),
('Butter Pecan', 'Buttery ice cream with pecan nuts', 5.99, 'butter-pecan.jpg', 1),
('Coffee Ice Cream', 'Coffee-infused ice cream', 5.99, 'coffee.jpg', 1),
('Rocky Road', 'Chocolate ice cream with marshmallows and nuts', 6.99, 'rocky-road.jpg', 1),
('Pistachio Ice Cream', 'Nutty pistachio flavored ice cream', 5.99, 'pistachio.jpg', 1),
('Neapolitan Ice Cream', 'Combination of vanilla, chocolate, and strawberry', 5.99, 'neapolitan.jpg', 1);

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

INSERT INTO `customer_registration_log` (`customer_id`, `registration_date`, `source`) VALUES
(1, CURDATE(), 'website'),
(2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'mobile_app'),
(3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'website'),
(4, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'website'),
(5, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'mobile_app');

-- Then insert into tables that depend on the previous tables
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

INSERT INTO `monthly_sales` (`month`, `year`, `product_id`, `category_id`, `total_sales`, `units_sold`) VALUES
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 1, 8500.50, 1400),
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 2, 5200.75, 650),
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 3, 2100.25, 420),
(MONTH(CURDATE()), YEAR(CURDATE()), NULL, 4, 1500.00, 300);

-- Additional monthly sales data
INSERT INTO `monthly_sales` (`month`, `year`, `product_id`, `category_id`, `total_sales`, `units_sold`) VALUES
(MONTH(CURDATE()), YEAR(CURDATE()), 1, 1, 2500.75, 500),
(MONTH(CURDATE()), YEAR(CURDATE()), 2, 1, 1800.50, 300),
(MONTH(CURDATE()), YEAR(CURDATE()), 3, 1, 1200.25, 200),
(MONTH(CURDATE()), YEAR(CURDATE()), 4, 1, 3000.00, 400)
ON DUPLICATE KEY UPDATE
  total_sales = VALUES(total_sales),
  units_sold = VALUES(units_sold),
  updated_at = CURRENT_TIMESTAMP;

-- Dashboard stats
INSERT INTO `dashboard_stats` (`stat_date`, `new_customer`, `total_sales`, `new_orders`, `total_products`) VALUES
(DATE_SUB(CURDATE(), INTERVAL 3 DAY), 4, 1125.50, 10, 24),
(DATE_SUB(CURDATE(), INTERVAL 4 DAY), 6, 1350.25, 13, 24),
(DATE_SUB(CURDATE(), INTERVAL 5 DAY), 2, 950.75, 7, 23),
(DATE_SUB(CURDATE(), INTERVAL 6 DAY), 5, 1425.00, 11, 23)
ON DUPLICATE KEY UPDATE 
    `new_customer` = VALUES(`new_customer`),
    `total_sales` = VALUES(`total_sales`),
    `new_orders` = VALUES(`new_orders`),
    `total_products` = VALUES(`total_products`),
    `updated_at` = CURRENT_TIMESTAMP();

-- Create stored procedure
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