<?php
require 'config.inc.php';
require('models/element.php');
$t = new Template('another-page');
header('Content-type: text/html; charset=utf-8');
$substitutes = array();
$element = new Element();
foreach($t->variables as $key => $val){
  $e = $element->find_by_signature($key);
  $substitutes[$e->source] = call_user_func($e->format, $e->content);
}
print clean_output($t->populate($substitutes));
$end=microtime(); 
$end=explode(" ",$end); 
$end=$end[1]+$end[0]; 
if(MAR_DEVELOPER_MODE) printf("<!-- Page generated in %f seconds. -->",$end-$start); 
?>