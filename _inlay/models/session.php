<?php
class Session extends MiniActiveRecord{
  function authenticate($username, $password){
    $u = new User();
    if($user = $u->find_by_email($username)){
      if(strlen($user->encrypted_password) > 8){
        if(crypt($password, $user->encrypted_password) == $user->encrypted_password){
          return $user;
        }
      }
    }
    return false;
  }
}

?>