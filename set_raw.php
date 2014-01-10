<?php
require( 'config.inc.php' );
require('models/element.php');
$element = new Element();
$e = $element->find_by_signature($_POST['key']);
if(!$e){
  $e = $element->build(array('signature' => $_POST['key']));
}
if($e){
  $e->content = $_POST['val'];
  $e->source = $_POST['source'];
  $e->format = $_POST['format'];
  $e->save();
}
header('Content-type: text/html; charset=utf-8');
if($_POST['format'] == 'markdown'){
  print markdown($e->content);
  exit;
}
print $e->content;
?>