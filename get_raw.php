<?php
require( 'config.inc.php' );
$template_path = (string)$_POST['source'];
$template = file_get_contents('./' . $template_path);
$xml = simplexml_load_string($template);
require('test_data.php');
header('Content-type: text/plain; charset=utf-8');
print $params[$_POST['key']];
?>