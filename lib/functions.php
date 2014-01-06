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
  $class = '';
  if($element->attributes() && $element->attributes()->{'class'} && $element->attributes()->{'class'}->{0}){
    $class = (string) $element->attributes()->{'class'}->{0};
    if( ! stristr($class, $string)){
      $element->attributes()->{'class'} = $class . ' ' . $string;
    }
  }else{
    $element->addAttribute('class',$string);
  }
  return $element;
}
function present($key, $arr){
  return (isset($arr[$key]) && !empty($arr[$key]));
}
function clean_output($value=''){
  return str_replace(array('<html><head>', '</body></html>'), array("<html>\n  <head>", "  </body>\n</html>"), $value);
}
?>