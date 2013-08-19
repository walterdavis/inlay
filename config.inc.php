<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
require('lib/markdown.php');
require('lib/smartypants.php');
require('lib/tidy.php');
function xml_to_array($xml){
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);
  return $array;
}
function markdown($string){
  return SmartyPants(convert_markdown($string));
}
function string($string){
  return SmartyPants($string);
}
function date_string($string){
  return $string;
}
?>