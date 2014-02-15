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
if(1 == 1 && count($template->fields) > 0){
  $style = $head->item(0)->appendChild($template->doc->createElement('link'));
  $style->setAttribute('type', 'text/css');
  $style->setAttribute('rel', 'stylesheet');
  $style->setAttribute('href', '/' . join_path(array(ROOT_FOLDER , '/_inlay/css/show.css')));
  $body = $xpath->query('//body');
  $bar = $template->doc->createElement('div');
  $bar->setAttribute('class', 'title-show-bar');
  $body->item(0)->appendChild($bar);
  $button = $template->doc->createElement('a');
  $button->setAttribute('id', 'inlay-icon');
  $button->setAttribute('href', $_SERVER['REQUEST_URI'] . '?edit=true');
  $button->setAttribute('title', 'Edit this page');
  $button->nodeValue = 'i';
  $bar->appendChild($button);  
}
print clean_output($template->populate($substitutes));
if(MAR_DEVELOPER_MODE) printf("<!-- Generated with love, by Inlay, in %f seconds. -->", timing()); 
?>