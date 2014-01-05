<?php
// MiniActiveRecord
define('MAR_DSN', 'mysql://root:2lsd9_skdF@localhost/fw_cms');
define('MAR_LIMIT', 10000);
define('MAR_DEVELOPER_MODE', true);
define('MAR_CHARSET', 'UTF-8');
define('DB_CHARSET', 'utf8');
date_default_timezone_set('UTC');
require('lib/Inflector.php');
require('lib/MiniActiveRecord.php');

define('SALT', 'WWJlcnogdmNmaHogcWJ5YmUgZnZnIG56cmcsIHBiYWZycGdyZ2hlIG5xdmN2ZnZwdmF0IHJ5dmc=');
require('lib/markdown.php');
require('lib/smartypants.php');
require('lib/tidy.php');
require('lib/functions.php');
require('models/value.php');
require('lib/HTML5/Parser.php');
define('ROOT', dirname(__FILE__));
?>