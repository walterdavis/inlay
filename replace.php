<?php
// ob_start('ob_tidyhandler');
ini_set('display_errors', true);
error_reporting(E_ALL);
require('markdown.php');
require('smartypants.php');
$tpl = file_get_contents('page.html');
$pat = '/<([^>]+)>([^<]*?)\{\{(.+?)\}\}/';
// $matches = array();
// preg_match_all($pat, $tpl, &$matches);
function markdown($string){
  return SmartyPants(convert_markdown($string));
}
function string($string){
  return SmartyPants($string);
}
function date_string($string){
  return $string;
}
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
$options = array(
  'anchor-as-name' => false,
  'break-before-br' => true,
  'char-encoding' => 'utf-8',
  'decorate-inferred-ul' => false,
  'drop-empty-paras' => false,
  'drop-font-tags' => true,
  'drop-proprietary-attributes' => false,
  'force-output' => false,
  'hide-comments' => false,
  'indent' => true,
  'indent-attributes' => false,
  'indent-spaces' => 2,
  'join-styles' => false,
  'logical-emphasis' => false,
  'merge-divs' => false,
  'merge-spans' => false,
  'new-blocklevel-tags' => 'article aside audio details dialog figcaption figure footer header hgroup menutidy nav section source summary track video',
  'new-empty-tags' => 'command embed keygen source track wbr',
  'new-inline-tags' => 'canvas command data datalist embed keygen mark meter output progress time wbr',
  'newline' => 0,
  'numeric-entities' => false,
  'output-bom' => false,
  'output-encoding' => 'utf-8',
  'output-xhtml' => true,
  'preserve-entities' => true,
  'quiet' => true,
  'quote-ampersand' => true,
  'quote-marks' => false,
  'repeated-attributes' => 1,
  'show-body-only' => false,
  'show-warnings' => false,
  'sort-attributes' => 1,
  'tab-size' => 2,
  'tidy-mark' => false,
  'vertical-space' => true,
  'wrap' => 0 );
$output = tidy_parse_string($output, $options);
print($output);
?>