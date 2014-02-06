<?php
require 'config.inc.php';
$_SESSION['current_user'] = false;
header('Location: /' . join_path(array(ROOT_FOLDER, '/_inlay')) . '/login.php');
?>
