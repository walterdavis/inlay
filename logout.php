<?php
require 'config.inc.php';
$_SESSION['current_user'] = false;
header('Location: ' . ROOT_FOLDER . '/login.php');
?>
