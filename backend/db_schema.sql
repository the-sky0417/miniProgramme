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

INSERT INTO `user` (`wechat_name`, `avatar`, `openid`, `create_time`) VALUES
  ('测试用户', 'https://img.yzcdn.cn/vant/cat.jpeg', 'openid_test_user', NOW());

INSERT INTO `goods` (`name`, `price`, `cover`, `detail`, `stock`, `category_id`, `create_time`) VALUES
  ('小米手机 13', 3999.00, 'https://img.yzcdn.cn/vant/apple-1.jpg', '旗舰骁龙8+，超感光主摄，120Hz 高刷屏。', 100, 1, NOW()),
  ('无线蓝牙耳机', 199.00, 'https://img.yzcdn.cn/vant/slide-2.jpg', '高清音质，降噪体验，持久续航。', 250, 2, NOW()),
  ('智能手环', 129.00, 'https://img.yzcdn.cn/vant/flower-1.jpg', '心率监测，运动记录，睡眠分析。', 180, 2, NOW()),
  ('运动跑鞋', 349.00, 'https://img.yzcdn.cn/vant/leaf.jpg', '轻便透气，缓震稳定，适合每日跑步。', 150, 3, NOW()),
  ('便携式移动电源', 159.00, 'https://img.yzcdn.cn/vant/food-1.jpg', '10000mAh 大容量，双口快充，旅行必备。', 200, 2, NOW()),
  ('家用台灯', 89.00, 'https://img.yzcdn.cn/vant/tea-2.jpg', '护眼无蓝光，三档调光，学生学习照明。', 120, 4, NOW()),
  ('双肩背包', 229.00, 'https://img.yzcdn.cn/vant/cat.jpeg', '防泼水面料，多功能收纳，通勤旅行两相宜。', 135, 3, NOW());

DROP TABLE IF EXISTS `shopping_cart`;
CREATE TABLE `shopping_cart` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `goods_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `create_time` DATETIME NOT NULL,
  `update_time` DATETIME NOT NULL,
  UNIQUE KEY `user_goods` (`user_id`,`goods_id`),
  INDEX (`user_id`),
  INDEX (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

