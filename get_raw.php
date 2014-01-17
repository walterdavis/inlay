<?php
require( 'config.inc.php' );
if(!$current_user){
  missing();
}
require('models/element.php');
$element = new Element();
header('Content-type: text/plain; charset=utf-8');
$e = $element->find_by_signature($_POST['key']);
if($e){
  print $e->content;
}
?>