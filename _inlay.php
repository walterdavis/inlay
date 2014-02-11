<?php
require('_inlay/config.inc.php');
$template_path = (string) $_GET['path'];

if(substr($template_path, -5) != '.html'){
  $template_path = (substr($template_path, -1) == '/') ? $template_path . 'index.html' : $template_path . '/index.html';
}
if(file_exists('./' . $template_path)){
  $template = new Template($template_path);
}elseif(present('virtual', $_GET)){
  require('_inlay/models/page.php');
  $page = new Page();
  $signature = md5($_SERVER['SERVER_NAME'] . ROOT_FOLDER . $template_path . SALT);
  if($p = $page->find_by_signature($signature)){
    $template = new Template($p->template);
  }else{
    //missing();
    //need to check if the user has a cookie at this point, and 404 if not.
    redirect_to('/_inlay/choose_template.php?path=' . rawurlencode($template_path));
  }
}else{
  missing();
}
if(present('raw', $_GET) && MAR_DEVELOPER_MODE){
  header('Content-type: text/plain; charset=utf-8');
  print($template->raw_template);
  printf("\n<!-- Page generated in %f seconds. -->", timing());
  exit;
}elseif(present('preview', $_GET)){
  print($template->raw_template);
  exit;
}elseif(present('edit',$_GET)){
  require('_inlay/edit.php');
  exit;
}else{
  require('_inlay/show.php');
}
?>