<?php
/*
  CREATE TABLE `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `first_name` varchar(255) NOT NULL DEFAULT '',
    `last_name` varchar(255) NOT NULL DEFAULT '',
    `email` varchar(255) NOT NULL DEFAULT '',
    `encrypted_password` varchar(255) NOT NULL DEFAULT '',
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
*/
class User extends MiniActiveRecord{
  public $validations = 'presence:first_name; presence:last_name; presence:email; email:email; regexp:password:/^.{8,100}$/:Password must be at least 8 characters long; password_confirmation:password_confirmation';
  function set_encrypted_password($password) {
    $salt = '';
    for ($i = 0; $i < CRYPT_SALT_LENGTH; $i++) {
      $salt .= substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 1);
    }
    $this->encrypted_password = crypt($password, $salt);
  }
  private function validate_password_confirmation(){
    if(!empty($this->password) && $this->password == $this->password_confirmation){
      return true;
    }else{
      $this->add_error('encrypted_password', 'Passwords must match');
      return false;
    }
  }
  
}
?>