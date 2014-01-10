<?php
require('config.inc.php');
$template_path = (string) $_GET['path'];
if(substr($template_path, -5) != '.html'){
  $template_path = (substr($template_path, -1) == '/') ? $template_path . 'index.html' : $template_path . '/index.html';
}
if(file_exists('./' . $template_path)){
  $template = new Template($template_path);
}elseif(present('virtual', $_GET)){
  // put database lookup here to find virtual page
  print 'virtual';
  exit;
}else{
  header("HTTP/1.0 404 Not Found", true, 404);
  print '<html><title>404 Not Found</title><body><h1>Not Found</h1><p>The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found on this server.</p><hr />' . $_SERVER['SERVER_SIGNATURE'] . '</body></html>';
  exit;
}
if(present('raw', $_GET)){
  header('Content-type: text/plain; charset=utf-8');
  exit;
}elseif(present('edit',$_GET)){
  require('edit.php');
  exit;
}else{
  require('show.php');
}
?>