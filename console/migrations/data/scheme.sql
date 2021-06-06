#CREATE DATABASE IF NOT EXISTS task_force CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE task_force;

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `icon` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` char(64) NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);

CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(255) NOT NULL,
  `lat` DECIMAL(10,8) NOT NULL,
  `lng` DECIMAL(11,8) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

CREATE TABLE IF NOT EXISTS `opinion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `owner_id` int(11) unsigned NOT NULL,
  `executor_id` int(11) unsigned NOT NULL,
  `rate` tinyint(3) unsigned NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_opinions_owner_user` (`owner_id`),
  KEY `FK_opinions_executor_user` (`executor_id`),
  CONSTRAINT `FK_opinions_executor_user` FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_opinions_owner_user` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
);

CREATE TABLE IF NOT EXISTS `user_notification` (
  `user_id` int(11) unsigned NOT NULL,
  `notification_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`notification_id`),
  KEY `notify_user_notify_fk` (`notification_id`),
  CONSTRAINT `notify_user_notify_fk` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`),
  CONSTRAINT `notify_user_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
);

CREATE TABLE IF NOT EXISTS `work` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `filename` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `file_user_fk` (`user_id`),
  CONSTRAINT `file_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `city_id` int(11) unsigned NOT NULL,
  `about` text,
  `user_id` int(11) unsigned NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `messenger` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) NOT NULL,
  `rate` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `role` enum('customer','executor') NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`id`),
  KEY `profile_user_fk` (`user_id`),
  KEY `FK_profile_city` (`city_id`),
  CONSTRAINT `FK_profile_city` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`),
  CONSTRAINT `profile_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS `specialization` (
  `profile_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`profile_id`,`category_id`),
  KEY `spec_category_fk` (`category_id`),
  CONSTRAINT `spec_category_fk` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `status_id` int(11) unsigned NOT NULL,
  `address` varchar(255) NOT NULL,
  `lat` DECIMAL(10,8) NOT NULL,
  `lng` DECIMAL(11,8) NOT NULL,
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
  CONSTRAINT `task_category_fk` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `task_customer_user_fk` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`),
  CONSTRAINT `task_executor_user_fk` FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS `response` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rate` tinyint(3) unsigned NOT NULL,
  `description` text NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  `price` int(10) unsigned DEFAULT NULL,
  `status` enum('new','confirmed','canceled') NOT NULL DEFAULT 'new',
  PRIMARY KEY (`id`),
  KEY `response_task_fk` (`task_id`),
  CONSTRAINT `responce_task_fk` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`)
);

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(11) unsigned NOT NULL,
  `filename` varchar(512) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `files_task_fk` (`task_id`),
  CONSTRAINT `files_task_fk` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`)
);

CREATE TABLE IF NOT EXISTS `favorite` (
  `user_id` int(11) unsigned NOT NULL,
  `favorite_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`favorite_id`),
  KEY `FK_favorite_files` (`favorite_id`),
  CONSTRAINT `FK_favorite_files` FOREIGN KEY (`favorite_id`) REFERENCES `file` (`id`),
  CONSTRAINT `FK_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
);


CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `consumer_id` int(11) unsigned NOT NULL,
  `executor_id` int(11) unsigned NOT NULL,
  `task_id` int(11) unsigned NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_chat_user` (`consumer_id`),
  KEY `FK_chat_user_2` (`executor_id`),
  KEY `FK_chat_task` (`task_id`),
  CONSTRAINT `FK_chat_task` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`),
  CONSTRAINT `FK_chat_user` FOREIGN KEY (`consumer_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_chat_user_2` FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`)
);
