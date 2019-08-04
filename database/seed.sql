# ************************************************************
# Sequel Pro SQL dump
# Version 5438
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 8.0.17)
# Database: ingaming
# Generation Time: 2019-08-04 12:07:30 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cart_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cart_products`;

CREATE TABLE `cart_products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) unsigned NOT NULL,
  `game_id` int(11) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  KEY `game_id` (`game_id`),
  CONSTRAINT `cart_products_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_products_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table carts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `carts`;

CREATE TABLE `carts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;

INSERT INTO `carts` (`id`, `user_id`)
VALUES
	(1,1);

/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;

INSERT INTO `categories` (`id`, `name`)
VALUES
	(00000000001,'action'),
	(00000000002,'adventure'),
	(00000000003,'racing'),
	(00000000004,'horror'),
	(00000000005,'shooter'),
	(00000000006,'role-playing');

/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table games
# ------------------------------------------------------------

DROP TABLE IF EXISTS `games`;

CREATE TABLE `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;

INSERT INTO `games` (`id`, `title`, `blurb`, `description`, `platform_id`, `category_id`, `price`, `release_date`, `rating`, `case_img`, `cover_img`, `trailer`, `featured`)
VALUES
	(1,'Sekiro','<p>Carve your own clever path to vengeance in an all-new adventure from developer FromSoftware, creators of Bloodborne and the Dark Souls series.</p>','<p>In Sekiro: Shadows Die Twice you are the one-armed wolf, a disgraced and disfigured warrior rescued from the brink of death. Bound to protect a young lord who is the descendant of an ancient bloodline, you become the target of many vicious enemies, including the dangerous Ashina clan. When the young lord is captured, nothing will stop you on a perilous quest to regain your honor, not even death itself.</p>',1,3,50.99,'2016-01-12',4,'case_img.jpeg','cover_img.jpg','https://www.youtube.com/embed/vrSY6E-TXg4',0),
	(2,'Bloodborne','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',1,5,45.99,'2018-09-05',2,'case_img.jpeg','cover_img.jpg','https://www.youtube.com/embed/G203e1HhixY',0),
	(3,'The Last Of Us','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',2,5,54.99,'2019-12-27',4,'case_img.jpg','cover_img.jpg','https://www.youtube.com/embed/W01L70IGBgE',0),
	(4,'Dark Souls 3','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',1,1,48.99,'2018-08-09',3,'case_img.jpg','cover_img.jpg','https://www.youtube.com/embed/cWBwFhUv1-8',0),
	(5,'God of War','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',2,4,45.99,'2017-09-29',2,'case_img.jpeg','cover_img.jpg','https://www.youtube.com/embed/K0u_kAWLJOA',1),
	(6,'Horizon: Zero Dawn','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',1,5,45.99,'2017-08-08',2,'case_img.jpeg','cover_img.jpg','https://www.youtube.com/embed/wzx96gYA8ek',0),
	(7,'The Witcher 3','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',3,5,47.99,'2019-06-18',2,'case_img.jpeg','cover_img.jpg','https://www.youtube.com/embed/XHrskkHf958',0),
	(8,'Uncharted','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus cursus sagittis tincidunt. Vivamus euismod turpis aliquam ullamcorper elementum.</p>',2,4,54.99,'2019-03-13',3,'case_img.jpg','cover_img.jpg','https://www.youtube.com/embed/-mH2SVyBMAQ',0);

/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table platforms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `platforms`;

CREATE TABLE `platforms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `platforms` WRITE;
/*!40000 ALTER TABLE `platforms` DISABLE KEYS */;

INSERT INTO `platforms` (`id`, `name`, `short_name`)
VALUES
	(1,'PlayStation 4','PS4'),
	(2,'Xbox One','Xbox One'),
	(3,'PC','PC');

/*!40000 ALTER TABLE `platforms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table transactions
# ------------------------------------------------------------

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
  `user_id` int(11) unsigned DEFAULT NULL,
  `cart_id` int(11) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `is_admin`)
VALUES
	(1,'Jordan','Walker','walker.jlg@gmail.com','$2y$10$Uv07XX1lWQH37fHx8XTbpu16hY.3OpQJmB6Wjp.K0JcHzCpfYLNja',1);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
