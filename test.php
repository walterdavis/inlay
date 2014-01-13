<?php
require 'config.inc.php';
header('Content-type: text/plain; charset=utf-8');
$directory = new RecursiveDirectoryIterator(ROOT);
$iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
$templates = array();
foreach ($iterator as $filename => $f) {
  if(preg_match('/\.html$/i', $filename)){
    $path = str_replace(ROOT . '/', '', $filename);
    $t = new Template($path);
    if(count($t->variables) > 0)
      $templates[$path] = $f->getFilename();
  }
}
ksort($templates);
print_r($templates);
?>