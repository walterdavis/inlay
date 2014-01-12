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
$xpath = new DomXPath($template->doc);
$head = $xpath->query('//head');
$top = $xpath->query('//head/meta');
$base = $template->doc->createElement('base');
$base->setAttribute('href', $template->base);
$head->item(0)->insertBefore($base, $top->item(0));
print clean_output($template->populate($substitutes));
$end=microtime(); 
$end=explode(" ",$end); 
$end=$end[1]+$end[0]; 
if(MAR_DEVELOPER_MODE) printf("<!-- Page generated in %f seconds. -->", timing()); 
?>