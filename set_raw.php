<?php
require( 'config.inc.php' );
if(present('current_user', $_SESSION)){
  $current_user = $_SESSION['current_user'];
}
if(!$current_user){
  missing();
}
require('models/element.php');
$element = new Element();
$e = $element->find_or_build_by_signature(md5($_POST['key'] . $_POST['uri']));
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