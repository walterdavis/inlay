<?php
// timing
$start=microtime(); 
$start=explode(" ",$start); 
$start=$start[1]+$start[0];
define('START', $start);
unset($start);
// Database Credentials Go Here:
//                         user:password@server/database
define('MAR_DSN', 'mysql://root:2lsd9_skdF@localhost/fw_cms');
define('MAR_LIMIT', 10000);
define('MAR_DEVELOPER_MODE', true);
define('MAR_CHARSET', 'UTF-8');
define('DB_CHARSET', 'utf8');
date_default_timezone_set('UTC');
require('lib/Inflector.php');
require('lib/MiniActiveRecord.php');
// templates and routing
define('SALT', 'WWJlcnogdmNmaHogcWJ5YmUgZnZnIG56cmcsIHBiYWZycGdyZ2hlIG5xdmN2ZnZwdmF0IHJ5dmc=');
require('lib/markdown.php');
require('lib/smartypants.php');
require('lib/tidy.php');
require('lib/functions.php');
require('lib/HTML5/Parser.php');
require 'lib/Template.php';
define('ROOT', dirname(dirname(__FILE__)));
$root_folder = str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT) . '/';
if($root_folder == '/') $root_folder = '';
define('ROOT_FOLDER', $root_folder);
unset($root_folder);
session_start();
$flash = '<div class="flash %s"><ul>%s</ul></div>';
?>