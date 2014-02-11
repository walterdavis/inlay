<?php
require 'config.inc.php';
$_SESSION['current_user'] = false;
redirect_to('/_inlay/login.php');
?>
