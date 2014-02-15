<?php
class Template{
  private $template_identifier = null;
  private $template = null;
  private $server = null;
  public $raw_template = null;
  public $xml = null;
  public $template_key = null;
  public $fields = array();
  public $variables = array();
  public $base = '';
  public $doc = null;
  function __construct($template_identifier){
    $this->template_identifier = $template_identifier;
    $this->template = $this->relative_path_to_template();
    $this->base = '/' . join_path(array(ROOT_FOLDER, dirname($this->template))) . '/';
    $this->base = preg_replace('/\/+/', '/', $this->base);
    $this->server = $_SERVER['HTTP_HOST'] . ROOT_FOLDER;
    $this->template_key = md5($this->server . $this->template . SALT);
    $this->raw_template = str_replace('%', '%%', file_get_contents($this->absolute_path_to_template()));
    $this->doc = HTML5_Parser::parse($this->raw_template);
    $this->xml = simplexml_import_dom($this->doc);
    $this->fields = $this->xml->xpath('//*[@data-inlay-source]');
    foreach($this->fields as $k => $variable){
      $key = (string) $variable->attributes()->{'data-inlay-source'}->{0};
      $crypt = md5($this->template_key . $key);
      $this->fields[$k]->addAttribute('data-inlay-key', $crypt);
      $format = 'string';
      if($variable->attributes() && $variable->attributes()->{'data-inlay-format'} && $variable->attributes()->{'data-inlay-format'}->{0}){
        $format = (string) $variable->attributes()->{'data-inlay-format'}->{0};
      }
      $this->variables[$crypt]['source'] = $key;
      $this->variables[$crypt]['format'] = $format;
    }
    return $this;
  }
  /**
   * location of the template relative to the site root
   *
   * @return string
   * @author Walter Lee Davis
   */
  function relative_path_to_template(){
    $template_path = ROOT . '/' . $this->template_identifier;
    $template_path = (substr($template_path, -5) == '.html') ? $template_path : $template_path . '.html';
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
  function populate($substitutes = array(), $strip = true){
    foreach($this->fields as $k => $variable){
      if($variable->attributes() && $strip){
        unset($variable->attributes()->{'data-inlay-format'});
        unset($variable->attributes()->{'data-inlay-source'});
        unset($variable->attributes()->{'data-inlay-key'});
      }
      if((string)$variable->getName() != 'meta'){
        $variable[0][0] = '%s';
      }else{
        $variable['content'] = '%s';
      }
    }
    if($strip) unset($this->xml->body->attributes()->{'data-inlay-key'});
    return vsprintf($this->xml->asXML(), $substitutes);
  }
}
?>