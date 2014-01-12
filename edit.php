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
$script = $head->item(0)->appendChild($template->doc->createElement('script'));
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', 'http://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js');
$script = $head->item(0)->appendChild($template->doc->createElement('script', "\n    var \$root_folder = '" . ROOT_FOLDER . "';\n  "));
$script->setAttribute('type', 'text/javascript');
$script = $head->item(0)->appendChild($template->doc->createElement('script'));
$script->setAttribute('type', 'text/javascript');
$script->setAttribute('src', ROOT_FOLDER . '/javascripts/editor.js');
$template->xml->body[0]->addAttribute('data-key', $template->template_key);
foreach($template->fields as $k => $field){
  if($field->attributes() && $field->attributes()->{'data-format'} && $field->attributes()->{'data-format'}->{0}){
    $format = (string) $field->attributes()->{'data-format'}->{0};
    add_class_name($field, 'editable');
    add_class_name($field, $format);
  }
}
print clean_output($template->populate($substitutes, false));
if(MAR_DEVELOPER_MODE) printf("<!-- Page generated in %f seconds. -->", timing()); 
?>