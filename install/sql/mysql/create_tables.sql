

CREATE TABLE IF NOT EXISTS `clients` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  PRIMARY KEY (`id`));


CREATE TABLE IF NOT EXISTS `merchandise` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `price` DECIMAL(10,2) NULL,
  PRIMARY KEY (`id`));

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `item_id` INT NULL,
  `customer_id` INT NULL,
  `comment` VARCHAR(1024) NULL,
  `status` ENUM('new', 'complete') NULL,
  `order_date` DATETIME NULL,
  PRIMARY KEY (`id`));

