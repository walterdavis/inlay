<?php
class Site extends MiniActiveRecord{
  public $validations = 'presence:url; presence:name; presence:user_id;';
  public $belongs_to = 'user';
}
?>