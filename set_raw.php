<?php
require( 'config.inc.php' );
header('Content-type: text/html; charset=utf-8');
if(file_exists('./data/' . $_POST['key'] . '.txt')){
  file_put_contents('./data/' . $_POST['key'] . '.txt', $_POST['val']);
}
if(isset($_POST['markdown']) && $_POST['markdown'] == 'true'){
  print markdown($_POST['val']);
  exit;
}
print $_POST['val'];
?>