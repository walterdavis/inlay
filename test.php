<?php
require 'config.inc.php';
require 'lib/Template.php';
$t = new Template('another-page');
header('Content-type: text/html; charset=utf-8');
print tidy($t->populate(array('keywords' => 'One, two, three', 'title' => 'The replacement title', 'headline' => 'The newer headline', 'left_column' => 'The left column has fun with this content.', 'right_column' => 'Right on!')));
?>