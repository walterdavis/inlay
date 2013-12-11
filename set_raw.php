<?php
require( 'config.inc.php' );
$template_path = (string)$_POST['source'];
$crypt = md5($_SERVER['HTTP_HOST'] . '/fwCMS/' . $template_path . $_POST['key'] . $salt);
file_put_contents('./data/' . $crypt . '.txt', $_POST['val']);
header('Content-type: text/html; charset=utf-8');
if(isset($_POST['markdown']) && $_POST['markdown'] == 'true'){
  print markdown($_POST['val']);
  exit;
}
print $_POST['val'];
?>