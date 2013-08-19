<?php
// ob_start('ob_tidyhandler');
require('config.inc.php');
$tpl = file_get_contents('page.html');
$pat = '/<([^>]+)>([^<]*?)\{\{(.+?)\}\}/';
// $matches = array();
// preg_match_all($pat, $tpl, &$matches);
$title = 'Title';
$headline = '"Headline"';
$date = 'Date';
$body = '##This is the body.

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

Duis aute irure dolor in "reprehenderit" in voluptate velit esse cillum dolore eu fugiat nulla pariatur.

- One
- Two
- Three

Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
function callback_for_replacement($matches){
  if(!empty($GLOBALS[$matches[3]])){
    if(!empty($matches[1])){
      $inner_match = array();
      preg_match('/^(.+?) data\-format="(.+?)"(.*?)$/', $matches[1], &$inner_match);
      if(isset($inner_match[2]) && !empty($inner_match[2])){
        return "<{$inner_match[1]}{$inner_match[3]}>" . call_user_func($inner_match[2],"{$GLOBALS[$matches[3]]}");
      }else{
        return "<{$matches[1]}>{$matches[2]}{$GLOBALS[$matches[3]]}";
      }
    }
  }
}
header('Content-type: text/html; charset=utf-8');
$output = preg_replace_callback($pat, 'callback_for_replacement', $tpl);
$output = tidy($output);
print($output);
?>