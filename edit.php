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
$head = $template->xml->head[0];
$script = $head->addChild('script');
$script->addAttribute('type', 'text/javascript');
$script->addAttribute('src', 'http://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js');
$script = $head->addChild('script');
$script->addAttribute('type', 'text/javascript');
$script->addAttribute('src', 'javascripts/editor.js');
$template->xml->body[0]->addAttribute('data-key', $template->template_key);
foreach($template->fields as $k => $field){
  if($field->attributes() && $field->attributes()->{'data-format'} && $field->attributes()->{'data-format'}->{0}){
    $format = (string) $field->attributes()->{'data-format'}->{0};
    add_class_name($field, 'editable');
    add_class_name($field, $format);
  }
}
print clean_output($template->populate($substitutes, false));
$end=microtime(); 
$end=explode(" ",$end); 
$end=$end[1]+$end[0]; 
if(MAR_DEVELOPER_MODE) printf("<!-- Page generated in %f seconds. -->",$end-$start); 




?>