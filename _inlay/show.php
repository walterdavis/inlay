<?php
require('models/element.php');
require('models/collection.php');
require('models/member.php');
header('Content-type: text/html; charset=utf-8');
$substitutes = array();
$element = new Element();
$collection = new Collection();
$member = new Member();
// foreach($template->collections as $k => $field){
//   $children = $template->xml->xpath('//*[@data-inlay-collection]/*');
//   foreach($children as $child){
//     unset($child[0]);
//   }
//   $c = $collection->find_by_name($field->attributes()->{'data-inlay-collection'}->{0});
//   foreach($c->members as $m => $member){
//     $s = array();
//     $t = new Template($member->partial, $m);
//     foreach($t->variables as $v => $variable){
//       $e = $element->find_by_signature($v);
//       $s[] = call_user_func($e->format, $e->content);
//     }
//     $part = simplexml_load_string($t->populate($s, true, true));
//     $field->addChild($part->getName(), $part->{0});
//   }
// }
// loop through collections, update the template with found children, populated
// with the virtual placeholders. once this is done, the next statement will work:
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
  dom_import_simplexml($c)->removeChild($temp);
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