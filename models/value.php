<?php
/*
  CREATE TABLE `values` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `key` char(32) NOT NULL DEFAULT '',
    `source` varchar(255) NOT NULL DEFAULT '',
    `format` varchar(255) DEFAULT 'string',
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/
class Value extends MiniActiveRecord{
  public $validations = 'presence:key; presence:source; presence:format';
}
?>