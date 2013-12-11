<?php
$xml = simplexml_load_string($template);
require('test_data.php');
$head = $xml->head[0];
$script = $head->addChild('script');
$script->addAttribute('type', 'text/javascript');
$script->addAttribute('src', 'http://ajax.googleapis.com/ajax/libs/prototype/1.7/prototype.js');
$script = $head->addChild('script');
$script->addAttribute('type', 'text/javascript');
$script->addAttribute('src', 'javascripts/editor.js');
foreach($variables as $k => $variable){
  $key = (string) $variable->attributes()->{'data-source'}->{0};
  $format = 'string';
  if($variable->attributes() && $variable->attributes()->{'data-format'} && $variable->attributes()->{'data-format'}->{0}){
    $format = (string) $variable->attributes()->{'data-format'}->{0};
    unset($variable->attributes()->{'data-format'});
    add_class_name($variable, 'editable');
    add_class_name($variable, $format);
  }
}
print tidy(vsprintf($xml->asXML(), $substitutes));
?>