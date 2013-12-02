<?php
require( 'config.inc.php' );
header('Content-type: text/plain; charset=utf-8');
print $params[$_POST['key']];
?>