<?php
require('config.inc.php');
$tpl = file_get_contents('page.html');
$title = 'Title';
$headline = 'Headline';
$date = 'Date';
$body = '##This is the body.

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

Duis aute irure dolor in "reprehenderit" in voluptate velit esse cillum dolore eu fugiat nulla pariatur.

- One
- Two
- Three

Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
header('Content-type: text/plain; charset=utf-8');
$xml = simplexml_load_string($tpl);
$variables = $xml->xpath('//title|//*[@data-format]');
foreach($variables as $variable){
  $key = trim((string) $variable->{0}, " \t\n\r\0\x0B{}");
  $format = 'string';
  if($variable->attributes() && $variable->attributes()->{'data-format'}){
    $format = (string) $variable->attributes()->{'data-format'}->{0};
    unset($variable->attributes()->{'data-format'});
  }
  $part = call_user_func($format,$GLOBALS[$key]);
  if(preg_match('/</', $part)){
    $part = simplexml_load_string("<root>{$part}</root>");
  }
  $variable->{0} = $part;
  print "{$key} ({$format})\n";
}
print tidy($xml->asXML());
?>