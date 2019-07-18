-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `cart_products`;
CREATE TABLE `cart_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  KEY `game_id` (`game_id`),
  CONSTRAINT `cart_products_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_products_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `cart_products` (`id`, `cart_id`, `game_id`, `quantity`) VALUES
(24,	16,	7,	1);

DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `carts` (`id`) VALUES
(16),
(17);

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`id`, `name`) VALUES
(1,	'action'),
(2,	'adventure'),
(3,	'racing'),
(4,	'horror'),
(5,	'shooter'),
(6,	'role-playing');

DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `blurb` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `platform_id` int(1) unsigned NOT NULL,
  `category_id` int(1) unsigned NOT NULL,
  `price` decimal(5,2) unsigned NOT NULL,
  `release_date` date NOT NULL,
  `rating` tinyint(1) unsigned NOT NULL,
  `case_img` varchar(255) DEFAULT NULL,
  `cover_img` varchar(255) DEFAULT NULL,
  `trailer` varchar(255) NOT NULL,
  `featured` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `games` (`id`, `title`, `blurb`, `description`, `platform_id`, `category_id`, `price`, `release_date`, `rating`, `case_img`, `cover_img`, `trailer`, `featured`) VALUES
(1,	'Sekiro',	'<p>Carve your own clever path to vengeance in an all-new adventure from developer FromSoftware, creators of Bloodborne and the Dark Souls series.</p>',	'<p>In Sekiro: Shadows Die Twice you are the one-armed wolf, a disgraced and disfigured warrior rescued from the brink of death. Bound to protect a young lord who is the descendant of an ancient bloodline, you become the target of many vicious enemies, including the dangerous Ashina clan. When the young lord is captured, nothing will stop you on a perilous quest to regain your honor, not even death itself.</p>',	1,	3,	50.99,	'2016-01-12',	4,	'case_img.jpeg',	'cover_img.jpg',	'https://www.youtube.com/embed/vrSY6E-TXg4',	0),
(2,	'Bloodborne',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	1,	5,	45.99,	'2018-09-05',	2,	'case_img.jpeg',	'cover_img.jpg',	'https://www.youtube.com/embed/G203e1HhixY',	0),
(3,	'The Last Of Us',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	2,	5,	54.99,	'2019-12-27',	4,	'case_img.jpg',	'cover_img.jpg',	'https://www.youtube.com/embed/W01L70IGBgE',	0),
(4,	'Dark Souls 3',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	1,	1,	48.99,	'2018-08-09',	3,	'case_img.jpg',	'cover_img.jpg',	'https://www.youtube.com/embed/cWBwFhUv1-8',	0),
(5,	'God of War',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	2,	4,	45.99,	'2017-09-29',	2,	'case_img.jpeg',	'cover_img.jpg',	'https://www.youtube.com/embed/K0u_kAWLJOA',	1),
(6,	'Horizon: Zero Dawn',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	1,	5,	45.99,	'2017-08-08',	2,	'case_img.jpeg',	'cover_img.jpg',	'https://www.youtube.com/embed/wzx96gYA8ek',	0),
(7,	'The Witcher 3',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	3,	5,	47.99,	'2019-06-18',	2,	'case_img.jpeg',	'cover_img.jpg',	'https://www.youtube.com/embed/XHrskkHf958',	0),
(8,	'Uncharted',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',	2,	4,	54.99,	'2019-03-13',	3,	'case_img.jpg',	'cover_img.jpg',	'https://www.youtube.com/embed/-mH2SVyBMAQ',	0);

DROP TABLE IF EXISTS `platforms`;
CREATE TABLE `platforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `platforms` (`id`, `name`, `short_name`) VALUES
(1,	'PlayStation 4',	'PS4'),
(2,	'Xbox One',	'Xbox One'),
(3,	'PC',	'PC');

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gateway` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `payment_id` varchar(100) NOT NULL,
  `amount` decimal(7,2) NOT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `card_brand` varchar(20) DEFAULT NULL,
  `last4` int(4) DEFAULT NULL,
  `name` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `address_1` varchar(60) NOT NULL,
  `address_2` varchar(60) DEFAULT NULL,
  `city` varchar(60) NOT NULL,
  `country` varchar(60) NOT NULL,
  `postcode` varchar(60) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `transactions` (`id`, `gateway`, `type`, `payment_id`, `amount`, `status`, `card_brand`, `last4`, `name`, `email`, `address_1`, `address_2`, `city`, `country`, `postcode`, `cart_id`, `created_at`, `updated_at`) VALUES
(17,	'stripe',	'card',	'pi_1EVxpPADp7wq6Sbk13CD51xS',	45.99,	'succeeded',	'visa',	4242,	'Jordan Walker',	'walker.jlg@gmail.com',	'19 Bodmin Avenue',	'wewewew',	'Shipley',	'UK',	'BD18 1LT',	16,	'2019-05-03 09:37:15',	'2019-05-03 09:37:16'),
(18,	'stripe',	'card',	'pi_1EVy5sADp7wq6SbkILlIGafJ',	45.99,	'succeeded',	'visa',	4242,	'Jordan Walker',	'walker.jlg@gmail.com',	'19 Bodmin Avenue',	'wewewew',	'Shipley',	'UK',	'BD18 1LT',	17,	'2019-05-03 09:54:16',	'2019-05-03 09:54:17'),
(19,	'braintree',	'sale',	'8rb847h1',	45.99,	'succeeded',	'Visa',	1111,	'Jordan Walker',	'walker.jlg@gmail.com',	'19 Bodmin Avenue',	'Wrose, Shipley',	'Bradford',	'UK',	'BD18 1LT',	17,	'2019-05-03 12:11:21',	'2019-05-03 12:11:21'),
(20,	'braintree',	'sale',	'9t8hcx19',	45.99,	'succeeded',	'Visa',	1111,	'Jordan Walker',	'walker.jlg@gmail.com',	'19 Bodmin Avenue',	'Wrose, Shipley',	'Bradford',	'UK',	'BD18 1LT',	17,	'2019-05-03 13:42:05',	'2019-05-03 13:42:05'),
(21,	'braintree',	'sale',	'52vz0rvt',	48.99,	'succeeded',	'Visa',	1111,	'Jordan Walker',	'walker.jlg@gmail.com',	'19 Bodmin Avenue',	'Wrose, Shipley',	'Bradford',	'UK',	'BD18 1LT',	17,	'2019-05-03 13:44:13',	'2019-05-03 13:44:13'),
(22,	'braintree',	'sale',	'763sftzx',	48.99,	'submitted_for_settlement',	'Visa',	1111,	'Jordan Walker',	'walker.jlg@gmail.com',	'19 Bodmin Avenue',	'',	'Shipley',	'United Kingdom',	'BD18 1LT',	17,	'2019-05-03 13:47:17',	'2019-05-03 13:47:17'),
(23,	'braintree',	'sale',	'eaktmb56',	94.98,	'submitted_for_settlement',	'Visa',	1111,	'Jordan Walker',	'walker.jlg@gmail.com',	'19 Bodmin Avenue',	'',	'Shipley',	'United Kingdom',	'BD18 1LT',	17,	'2019-05-03 14:12:40',	'2019-05-03 14:12:40'),
(24,	'braintree',	'sale',	'pcr5s2t9',	94.98,	'submitted_for_settlement',	'Visa',	1111,	'Jordan Walker',	'walker.jlg@gmail.com',	'19 Bodmin Avenue',	'',	'Shipley',	'United Kingdom',	'BD18 1LT',	17,	'2019-05-03 14:57:37',	'2019-05-03 14:57:37');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `cart_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `is_admin`, `cart_id`) VALUES
(1,	'Jordan',	'Walker',	'walker.jlg@gmail.com',	'$2y$10$Uv07XX1lWQH37fHx8XTbpu16hY.3OpQJmB6Wjp.K0JcHzCpfYLNja',	1,	17);

-- 2019-05-03 14:58:43
