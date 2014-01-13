<?php
/*
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
*/

class Page extends MiniActiveRecord{
  public $validations = 'presence:signature';
}
?>