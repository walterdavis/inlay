-- Create tables for the CMS

CREATE TABLE `elements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `signature` char(32) NOT NULL DEFAULT '',
  `source` varchar(255) DEFAULT '',
  `format` varchar(255) DEFAULT 'string',
  `content` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `signature` (`signature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `signature` char(32) NOT NULL DEFAULT '',
  `server` varchar(255) DEFAULT '',
  `template` varchar(255) DEFAULT '',
  `path` varchar(1024) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `signature` (`signature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sessions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `encrypted_password` varchar(255) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;