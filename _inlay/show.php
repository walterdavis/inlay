<?php
require('models/element.php');
header('Content-type: text/html; charset=utf-8');
$substitutes = array();
$element = new Element();
foreach($template->fields as $k => $field){
  $key = md5((string) $field->attributes()->{'data-inlay-key'}->{0} . $_SERVER['REDIRECT_URL']);
  $e = $element->find_by_signature($key);
  if($e){
    $substitutes[] = call_user_func($e->format, $e->content);
  }else{
    $substitutes[] = Inflector::humanize($field['data-inlay-source']) . ' is not defined.';
  }
}
$xpath = new DomXPath($template->doc);
$head = $xpath->query('//head');
$top = $xpath->query('//head/*[1]');
$base = $template->doc->createElement('base');
$base->setAttribute('href', $template->base);
$head->item(0)->insertBefore($base, $top->item(0));
print clean_output($template->populate($substitutes));
if(MAR_DEVELOPER_MODE) printf("<!-- Generated with love, by Inlay, in %f seconds. -->", timing()); 
?>