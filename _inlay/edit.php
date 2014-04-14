<?php
if(present('current_user', $_SESSION)){
  $current_user = $_SESSION['current_user'];
}
if(!$current_user){
  $_SESSION['next'] = $_SERVER['REQUEST_URI'];
  redirect_to('/_inlay/login.php');
}
require('models/element.php');
require('models/collection.php');
require('models/member.php');
header('Content-type: text/html; charset=utf-8');
$substitutes = array();
$element = new Element();
$collection = new Collection();
$member = new Member();
$xpath = new DomXPath($template->doc);
foreach($template->collections as $k => $c){
  $temp = dom_import_simplexml($c->children()->{0});
  $co = $collection->find_or_create_by_server_and_name($template->server, $c->attributes()->{'data-inlay-collection'});
  foreach($co->members as $key => $member){
    $clone = $temp->cloneNode(true);
    foreach($clone->childNodes as $child){
      if($child->hasAttribute('data-inlay-source')){
        $child->setAttribute('data-inlay-source', $c->attributes()->{'data-inlay-collection'} . '_' . $child->getAttribute('data-inlay-source') . '_' . $member->id);
      }
    }
    dom_import_simplexml($c)->appendChild($clone);
  }
  //dom_import_simplexml($c)->removeChild($temp);
  $temp->setAttribute('data-template', outerHTML($temp));
  $style = $classname = array();
  if($temp->hasAttribute('style')){
    $style[] = $temp->getAttribute('style');
  }
  $style[] = 'display:none';
  $temp->setAttribute('style', implode(' ', $style));
  if($temp->hasAttribute('class')){
    $classname[] = $temp->getAttribute('class');
  }
  $classname[] = 'inlay-template';
  $temp->setAttribute('class', implode(' ', $classname));
}
if(count($template->collections) > 0) $template->extract_fields();
foreach($template->fields as $k => $field){
  $key = md5((string) $field->attributes()->{'data-inlay-key'}->{0} . $_SERVER['REDIRECT_URL']);
  $e = $element->find_by_signature($key);
  if($e){
    $substitutes[] = call_user_func($e->format, $e->content);
  }else{
    $substitutes[] = Inflector::humanize($field['data-inlay-source']) . ' is not defined.';
  }
}
$head = $xpath->query('//head');
$top = $xpath->query('//head/*[1]');
$base = $template->doc->createElement('base');
$base->setAttribute('href', $template->base);
$head->item(0)->insertBefore($base, $top->item(0));
$script = $head->item(0)->appendChild($template->doc->createElement('script'));
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', 'http://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js');
$script = $head->item(0)->appendChild($template->doc->createElement('script'));
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', '/' . join_path(array(ROOT_FOLDER, '/_inlay/javascripts/control.textarea.js')));
$script = $head->item(0)->appendChild($template->doc->createElement('script'));
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', '/' . join_path(array(ROOT_FOLDER, '/_inlay/javascripts/control.textarea.markdown.js')));
$script = $head->item(0)->appendChild($template->doc->createElement('script', "\n    var \$root_folder = '" . ROOT_FOLDER . "';\n  "));
$script->setAttribute('type', 'text/javascript');
$script = $head->item(0)->appendChild($template->doc->createElement('script'));
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', '/' . join_path(array(ROOT_FOLDER, '/_inlay/javascripts/editor.js')));
$style = $head->item(0)->appendChild($template->doc->createElement('link'));
$style->setAttribute('type', 'text/css');
$style->setAttribute('rel', 'stylesheet');
$style->setAttribute('href', '/' . join_path(array(ROOT_FOLDER, '/_inlay/css/edit.css')));
$style = $head->item(0)->appendChild($template->doc->createElement('link'));
$style->setAttribute('type', 'text/css');
$style->setAttribute('rel', 'stylesheet');
$style->setAttribute('href', '/' . join_path(array(ROOT_FOLDER, '/_inlay/css/markdown.css')));
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