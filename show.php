<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
require('config.inc.php');
$tpl = file_get_contents('page.html');
//header('Content-type: text/plain; charset=utf-8');
$xml = simplexml_load_string($tpl);
$body = $xml->body[0];
$link = $body->addChild('a');
$link->addAttribute('class', 'edit-badge');
$link->addAttribute('href', 'edit.php');
$variables = $xml->xpath('//title|//*[@data-format]');
$substitutes = array();
foreach($variables as $k => $variable){
  $key = trim((string) $variable->{0}, " \t\n\r\0\x0B{}");
  $format = 'string';
  if($variable->attributes() && $variable->attributes()->{'data-format'}){
    $format = (string) $variable->attributes()->{'data-format'}->{0};
    unset($variable->attributes()->{'data-format'});
  }
  $substitutes[$k] = call_user_func($format,$params[$key]);
  $variable[0][0] = '%s';
}
print tidy(vsprintf($xml->asXML(), $substitutes));
?>