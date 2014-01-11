<?php
require('models/element.php');
header('Content-type: text/html; charset=utf-8');
$substitutes = array();
$element = new Element();
foreach($template->variables as $key => $val){
  $e = $element->find_by_signature($key);
  if($e){
    $substitutes[$e->source] = call_user_func($e->format, $e->content);
  }else{
    $substitutes[$val['source']] = Inflector::humanize($val['source']) . ' is not defined.';
  }
}
print clean_output($template->populate($substitutes));
$end=microtime(); 
$end=explode(" ",$end); 
$end=$end[1]+$end[0]; 
if(MAR_DEVELOPER_MODE) printf("<!-- Page generated in %f seconds. -->", timing()); 
?>