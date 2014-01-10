<?php
require 'config.inc.php';
require('models/element.php');
$element = new Element();
$e = $element->find_or_build_by_signature('hello');
print_r($e);
?>