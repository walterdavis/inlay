<?php
require('config.inc.php');
header('Content-type: text/plain');
print "Inlay (alpha) setup utility\n===========================\n\n";
if( ! function_exists('apache_get_modules') ){ die(phpinfo()); }
if(in_array('mod_rewrite', apache_get_modules())){
  print "Apache mod_rewrite is available.\n";
}else{
  die('Apache mod_rewrite is not available.');
}
if (version_compare(phpversion(), '5.3.0', '<')) {
  die('You need a newer version of PHP than ' . phpversion() . " to run Inlay.\n");
}else{
  print 'PHP ' . phpversion() . ' is new enough for Inlay.' . "\n";
}
if(@method_exists('PDO', 'getAvailableDrivers')){
  print "PDO bindings found:\n";
  print '  - ' . implode("\n  - ", PDO::getAvailableDrivers()) . "\n";
}else{
  die( 'PDO doesn’t seem to be installed.' . "\n" );
}
if($db = get_connection()){
  $tables = array();
  foreach($db->query('SHOW TABLES') as $row){
    $tables[] = $row[0];
  }
  print "\n";
  if(array('elements','pages','sessions','users') != $tables){
    $db->query(file_get_contents('bootstrap.sql'));
    print 'Database tables created!' . "\n";
  }else{
    print 'Database tables already exist.' . "\n";
  }
}else{
  die('Check your database username and password! Could not connect to MySQL.');
}
print '

Inlay is ready to go!
=====================

You may remove this file from your server if you want to,
although it does no harm to leave it in place.';
?>
