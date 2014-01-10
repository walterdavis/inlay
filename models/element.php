<?php
/*
  CREATE TABLE `elements` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `signature` char(32) NOT NULL DEFAULT '',
    `source` varchar(255) NOT NULL DEFAULT '',
    `format` varchar(255) DEFAULT 'string',
    `content` text,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
*/
class Element extends MiniActiveRecord{
  public $validations = 'presence:signature';
}
?>