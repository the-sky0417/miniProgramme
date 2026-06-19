CREATE DATABASE IF NOT EXISTS `mini_program` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mini_program`;

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `wechat_name` VARCHAR(100) NOT NULL,
  `avatar` VARCHAR(255) NOT NULL,
  `openid` VARCHAR(255) NOT NULL UNIQUE,
  `create_time` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `cover` VARCHAR(255) NOT NULL,
  `detail` TEXT NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `category_id` INT DEFAULT 0,
  `create_time` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_no` VARCHAR(100) NOT NULL,
  `user_id` INT NOT NULL,
  `goods_info` TEXT NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `create_time` DATETIME NOT NULL,
  INDEX (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`username`, `password`) VALUES ('admin', 'admin123');
