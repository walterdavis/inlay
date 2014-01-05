<?php
class Template{
  private $raw_template = null;
  private $template_identifier = null;
  private $template = null;
  private $server = null;
  public $template_key = null;
  public $variables = array();
  function __construct($template_identifier){
    $this->template_identifier = $template_identifier;
    $this->template = $this->relative_path_to_template();
    $this->server = $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
    $this->template_key = md5($this->server . $this->template . SALT);
    $this->raw_template = file_get_contents($this->absolute_path_to_template());
    $xml = simplexml_import_dom(HTML5_Parser::parse($this->raw_template));
    $variables = $xml->xpath('//*[@data-source]');
    foreach($variables as $k => $variable){
      $key = (string) $variable->attributes()->{'data-source'}->{0};
      $crypt = md5($this->template_key . $key);
      $format = 'string';
      if($variable->attributes() && $variable->attributes()->{'data-format'} && $variable->attributes()->{'data-format'}->{0}){
        $format = (string) $variable->attributes()->{'data-format'}->{0};
      }
      $this->variables[$crypt]['source'] = $key;
      $this->variables[$crypt]['format'] = $format;
    }
  }
  /**
   * location of the template relative to the site root
   *
   * @return string
   * @author Walter Lee Davis
   */
  function relative_path_to_template(){
    $template_path = './' . $this->template_identifier . '.html';
    if(!file_exists($template_path)){
      trigger_error('Template::relative_path_to_template(): Template file missing', E_USER_ERROR);
    }
    return str_replace(ROOT, '', realpath($template_path));
  }
  /**
   * full root-relative path to the template
   *
   * @return string
   * @author Walter Lee Davis
   */
  function absolute_path_to_template(){
    return ROOT . $this->relative_path_to_template();
  }
  /**
   * Populate the template with values
   *
   * @param array $substitutes 
   * @return string
   * @author Walter Lee Davis
   */
  function populate($substitutes = array()){
    $xml = simplexml_import_dom(HTML5_Parser::parse($this->raw_template));
    $variables = $xml->xpath('//*[@data-source]');
    foreach($variables as $k => $variable){
      if($variable->attributes()){
        unset($variable->attributes()->{'data-format'});
        unset($variable->attributes()->{'data-source'});
      }
      if((string)$variable->getName() != 'meta'){
        $variable[0][0] = '%s';
      }else{
        $variable['content'] = '%s';
      }
    }
    return vsprintf($xml->asXML(), $substitutes);
  }
}
?>