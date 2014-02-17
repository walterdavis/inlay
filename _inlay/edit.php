<?php
if(present('current_user', $_SESSION)){
  $current_user = $_SESSION['current_user'];
}else{
  $current_user = false;
}
if(!$current_user){
  $_SESSION['next'] = $_SERVER['REQUEST_URI'];
  redirect_to('/_inlay/login.php');
}
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
$script = $head->item(0)->appendChild($template->doc->createElement('script'));
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', 'http://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js');
$script = $head->item(0)->appendChild($template->doc->createElement('script', "\n    var \$root_folder = '" . ROOT_FOLDER . "';\n  "));
$script->setAttribute('type', 'text/javascript');
$script = $head->item(0)->appendChild($template->doc->createElement('script'));
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', '/' . join_path(array(ROOT_FOLDER, '/_inlay/javascripts/editor.js')));
$style = $head->item(0)->appendChild($template->doc->createElement('link'));
$style->setAttribute('type', 'text/css');
$style->setAttribute('rel', 'stylesheet');
$style->setAttribute('href', '/' . join_path(array(ROOT_FOLDER, '/_inlay/css/edit.css')));
$template->xml->body[0]->addAttribute('data-inlay-key', $template->template_key);
foreach($template->fields as $k => $field){
  if($field->attributes() && $field->attributes()->{'data-inlay-format'} && $field->attributes()->{'data-inlay-format'}->{0}){
    $format = (string) $field->attributes()->{'data-inlay-format'}->{0};
    add_class_name($field, 'editable');
    add_class_name($field, $format);
  }
}
print clean_output($template->populate($substitutes, false));
if(MAR_DEVELOPER_MODE) printf("<!-- Generated with love, by Inlay, in %f seconds. -->", timing()); 
?>