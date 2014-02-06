<?php
if(present('current_user', $_SESSION)){
  $current_user = $_SESSION['current_user'];
}
if(!$current_user){
  $_SESSION['next'] = $_SERVER['REQUEST_URI'];
  header('Location: /' . join_path(array(ROOT_FOLDER, '/_inlay')) . '/login.php');
  exit;
}
require('models/element.php');
header('Content-type: text/html; charset=utf-8');
$substitutes = array();
$element = new Element();
foreach($template->fields as $k => $field){
  $key = md5((string) $field->attributes()->{'data-key'}->{0} . $_SERVER['REDIRECT_URL']);
  $e = $element->find_by_signature($key);
  if($e){
    $substitutes[] = call_user_func($e->format, $e->content);
  }else{
    $substitutes[] = Inflector::humanize($field['data-source']) . ' is not defined.';
  }
}
$xpath = new DomXPath($template->doc);
$head = $xpath->query('//head');
$top = $xpath->query('//head/meta');
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
$script->setAttribute('src', ROOT_FOLDER . '/_inlay/javascripts/editor.js');
$script = $head->item(0)->appendChild($template->doc->createElement('link'));
$script->setAttribute('type', 'text/css');
$script->setAttribute('rel', 'stylesheet');
$script->setAttribute('href', ROOT_FOLDER . '/_inlay/css/edit.css');
$template->xml->body[0]->addAttribute('data-key', $template->template_key);
foreach($template->fields as $k => $field){
  if($field->attributes() && $field->attributes()->{'data-format'} && $field->attributes()->{'data-format'}->{0}){
    $format = (string) $field->attributes()->{'data-format'}->{0};
    add_class_name($field, 'editable');
    add_class_name($field, $format);
  }
}
print clean_output($template->populate($substitutes, false));
if(MAR_DEVELOPER_MODE) printf("<!-- Generated with love, by Inlay, in %f seconds. -->", timing()); 
?>