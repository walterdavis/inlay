<?php
$variables = $xml->xpath('//*[@data-source]');
$substitutes = array();
$params = array();
foreach($variables as $k => $variable){
  $key = (string) $variable->attributes()->{'data-source'}->{0};
  $crypt = md5($_SERVER['HTTP_HOST'] . '/fwCMS/' . $template_path . $key . $salt);
  if( ! file_exists('./data/' . $crypt . '.txt')){
    file_put_contents('./data/' . $crypt . '.txt', 'initial value');
  }
  $params[$key] = file_get_contents('./data/' . $crypt . '.txt');
  $format = 'string';
  if($variable->attributes() && $variable->attributes()->{'data-format'}){
    $format = (string) $variable->attributes()->{'data-format'}->{0};
  }
  $substitutes[$k] = call_user_func($format,$params[$key]);
  $variable[0][0] = '%s';
}
?>