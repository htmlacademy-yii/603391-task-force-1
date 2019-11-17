-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               8.0.15 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных taskforce
DROP DATABASE IF EXISTS `taskforce`;
CREATE DATABASE IF NOT EXISTS `taskforce` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `taskforce`;

-- Дамп структуры для таблица taskforce.categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.chat
DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `consumer_id` int(11) unsigned NOT NULL,
  `executor_id` int(11) unsigned NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_chat_user` (`consumer_id`),
  KEY `FK_chat_user_2` (`executor_id`),
  KEY `FK_chat_task` (`task_id`),
  CONSTRAINT `FK_chat_task` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`),
  CONSTRAINT `FK_chat_user` FOREIGN KEY (`consumer_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_chat_user_2` FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.cities
DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(255) NOT NULL,
  `lat` point NOT NULL,
  `long` point NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.favorite
DROP TABLE IF EXISTS `favorite`;
CREATE TABLE IF NOT EXISTS `favorite` (
  `user_id` int(11) unsigned NOT NULL,
  `favorit_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`favorit_id`),
  KEY `FK_favorite_files` (`favorit_id`),
  CONSTRAINT `FK_favorite_files` FOREIGN KEY (`favorit_id`) REFERENCES `files` (`id`),
  CONSTRAINT `FK_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.files
DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(11) unsigned NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `files_task_fk` (`task_id`),
  CONSTRAINT `files_task_fk` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.notification
DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.opinions
DROP TABLE IF EXISTS `opinions`;
CREATE TABLE IF NOT EXISTS `opinions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_add` timestamp NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `executor_id` int(11) unsigned NOT NULL,
  `rate` int(11) unsigned NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_opinions_owner_user` (`owner_id`),
  KEY `FK_opinions_executor_user` (`executor_id`),
  CONSTRAINT `FK_opinions_executor_user` FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_opinions_owner_user` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.profiles
DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `birthday` timestamp NOT NULL,
  `city` int(11) unsigned NOT NULL,
  `about` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `messenger` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) NOT NULL,
  `rate` int(10) unsigned NOT NULL DEFAULT '0',
  `role` enum('customer','executor') NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`id`),
  KEY `profile_user_fk` (`user_id`),
  CONSTRAINT `profile_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.response
DROP TABLE IF EXISTS `response`;
CREATE TABLE IF NOT EXISTS `response` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rate` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  `your_price` int(10) unsigned NOT NULL,
  `status` enum('new','in_work','refused') NOT NULL DEFAULT 'new',
  PRIMARY KEY (`id`),
  KEY `responce_task_fk` (`task_id`),
  CONSTRAINT `responce_task_fk` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.specialisation
DROP TABLE IF EXISTS `specialisation`;
CREATE TABLE IF NOT EXISTS `specialisation` (
  `profile_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`profile_id`,`category_id`),
  KEY `spec_category_fk` (`category_id`),
  CONSTRAINT `spec_category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.status
DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.task
DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `status_id` int(11) unsigned NOT NULL,
  `address` varchar(255) NOT NULL,
  `lat` point NOT NULL,
  `long` point NOT NULL,
  `budget` int(11) unsigned NOT NULL,
  `expire` timestamp NOT NULL,
  `date_add` timestamp NOT NULL,
  `executor_id` int(11) unsigned DEFAULT NULL,
  `customer_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task_category_fk` (`category_id`),
  KEY `task_executor_user_fk` (`executor_id`),
  KEY `task_customer_user_fk` (`customer_id`),
  KEY `FK_task_status` (`status_id`),
  FULLTEXT KEY `name` (`name`),
  CONSTRAINT `FK_task_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `task_category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `task_customer_user_fk` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`),
  CONSTRAINT `task_executor_user_fk` FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` char(64) NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.user_notification
DROP TABLE IF EXISTS `user_notification`;
CREATE TABLE IF NOT EXISTS `user_notification` (
  `user_id` int(11) unsigned NOT NULL,
  `notification_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`notification_id`),
  KEY `notify_user_notify_fk` (`notification_id`),
  CONSTRAINT `notify_user_notify_fk` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`),
  CONSTRAINT `notify_user_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
-- Дамп структуры для таблица taskforce.work_photo
DROP TABLE IF EXISTS `work_photo`;
CREATE TABLE IF NOT EXISTS `work_photo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `file_user_fk` (`user_id`),
  CONSTRAINT `file_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
