<?php
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