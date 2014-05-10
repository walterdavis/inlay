<?php
function xml_to_array($xml){
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);
  return $array;
}
function markdown($string){
  return SmartyPants(convert_markdown($string));
}
function string($string){
  return SmartyPants($string);
}
function date_string($string){
  return $string;
}
function add_class_name($element, $string){
  $class = '';
  if($element->attributes() && $element->attributes()->{'class'} && $element->attributes()->{'class'}->{0}){
    $class = (string) $element->attributes()->{'class'}->{0};
    if( ! stristr($class, $string)){
      $element->attributes()->{'class'} = $class . ' ' . $string;
    }
  }else{
    $element->addAttribute('class',$string);
  }
  return $element;
}
function present($key, $arr){
  return (isset($arr[$key]) && !empty($arr[$key]));
}
function clean_output($value=''){
  $value = preg_replace('/<script(.+?)\/>/', "  <script$1>\n  </script>", $value);
  $value = str_replace(array('<html><head>', '</body></html>', '</script></head>', '</script>  <script', '/><meta', '&lt!--', '--&gt;'), array("<html>\n  <head>", "  </body>\n</html>", "</script>\n  </head>", "</script>\n  <script", "/>\n  <meta", '<!--','-->'), $value);
  return $value;
}
function timing(){
  $end=microtime(); 
  $end=explode(" ",$end); 
  $end=$end[1]+$end[0]; 
  return $end - START;
}
function missing(){
  header("HTTP/1.0 404 Not Found", true, 404);
  print '<html><title>404 Not Found</title><body><h1>Not Found</h1><p>The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found on this server.</p><hr />' . $_SERVER['SERVER_SIGNATURE'] . '</body></html>';
  exit;
}
function join_path($parts=array()){
  $out = array();
  foreach($parts as $part){
    $p = trim($part," \t\n\r\0\x0B/\\");
    if(strlen($p) > 0) $out[] = $p;
  }
  return implode(DIRECTORY_SEPARATOR, $out);
}
function get_connection(){
  $params = parse_url(MAR_DSN);
  $dsn = sprintf('%s:host=%s;dbname=%s', $params['scheme'], $params['host'], str_replace('/','',$params['path']));
  $db = new PDO($dsn, $params['user'],$params['pass'], array(
    PDO::ATTR_PERSISTENT => true
  ));
  $db->exec("SET NAMES '" . DB_CHARSET . "';");
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
  return $db;
}
function redirect_to($path){
  header('Location: http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . (($_SERVER['SERVER_PORT'] == '80') ? '' : ':' . $_SERVER['SERVER_PORT']) . preg_replace('/\/+/', '/', '/' . join_path(array(ROOT_FOLDER, $path))));
  exit;
}
function outerHTML($el) {
  $doc = new DOMDocument();
  $doc->appendChild($doc->importNode($el, TRUE));
  return trim($doc->saveHTML());
}
?>