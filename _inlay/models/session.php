<?php
/*
  CREATE TABLE `sessions` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/
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