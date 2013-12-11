<?php
require('config.inc.php');
$template = '';
$template_path = (string) $_GET['page'] . '.html';
if(file_exists('./' . $template_path)){
  $template = file_get_contents('./' . $template_path);
}else{
  header("HTTP/1.0 404 Not Found", true, 404);
  print '<h1>Missing</h1><p>Sorry, that file could not be found.</p>';
  exit;
}
if(present('raw', $_GET)){
  header('Content-type: text/plain; charset=utf-8');
  print $template;
  exit;
}elseif(present('edit',$_GET)){
  require('edit.php');
  exit;
}else{
  require('show.php');
}
?>