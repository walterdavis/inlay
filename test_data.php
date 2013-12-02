<?php
$params = array();
foreach(w('headline title date body') as $key){
  $params[$key] = file_get_contents('./data/' . $key . '.txt');
}
?>