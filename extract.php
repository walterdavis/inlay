<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
$tpl = file_get_contents('page.html');
$pat = '/<([^>]+)>([^<]*?)\{\{(.+?)\}\}/';
$matches = $output = array();
preg_match_all($pat, $tpl, &$matches);
header('Content-type: text/plain; charset=utf-8');
foreach($matches[3] as $k => $v){
  $out[$k] = $v;
  if(preg_match('/data\-format/', $matches[1][$k])){
    $out[$k] .= ' (' . preg_replace('/^.+?data\-format="(.+?)".*$/', '$1', $matches[1][$k]) . ')';
  }
}
print_r($out);
?>