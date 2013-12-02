<?php
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
function add_class_name($element, $string){
  if($element->attributes() && $element->attributes()->{'class'}){
    $class = (string) $element->attributes()->{'class'}->{0};
    if( ! stristr($class, $string)){
      $element->attributes()->{'class'} = $class . ' ' . $string;
    }
  }else{
    $element->addAttribute('class',$string);
  }
  return $element;
}
function w($str){
  return preg_split('/\s+/', $str, -1, PREG_SPLIT_NO_EMPTY);
}
?>