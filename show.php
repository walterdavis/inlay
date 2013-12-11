<?php
header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_string($template);
require('test_data.php');
foreach($variables as $k => $variable){
  if($variable->attributes()){
    unset($variable->attributes()->{'data-format'});
    unset($variable->attributes()->{'data-source'});
  }
}
$body = $xml->body[0];
$link = $body->addChild('a');
$link->addAttribute('class', 'edit-badge');
$link->addAttribute('href', '?edit=' . time());
print tidy(vsprintf($xml->asXML(), $substitutes));
?>