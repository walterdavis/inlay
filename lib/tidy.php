<?php
function tidy($string){
  $string = preg_replace('/\<\?xml version="1\.0"\?\>\s/', '', $string);
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
  'newline' => 'LF',
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
  return tidy_parse_string($string, $options);
}

?>