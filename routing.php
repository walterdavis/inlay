<?php
$tpl = (string) $_GET['page'] . '.html';
if(file_exists('./' . $tpl)){
  $tpl = file_get_contents('./' . $tpl);
}
header('Content-type: text/html; charset=utf-8');
print $tpl;
?>