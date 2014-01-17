<?php
require 'config.inc.php';
require('models/user.php');
$error = '';
$user = new User();
if(present('commit', $_POST)){
  $user->populate($_POST);
  if($user->password != $_POST['password_confirmation']) $user->add_error('encrypted_password', 'Passwords must match');
  $user->set_encrypted_password($_POST['password']);
  if($user->save()){
    $_SESSION['current_user'] = $user->id;
    header('Location: page.html');
    exit;
  }else{
    foreach($user->get_errors() as $e){
      $error .= "<li>$e</li>";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Log In</title>
  <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8"/>
</head>
<body>
  <div id="PageDiv">
    <form action="sign_up.php" method="post" accept-charset="utf-8">
      <h1 id="please_log_in">Please Log In</h1>
      <fieldset>
        <?php
        if(!!$error){
          printf($flash, 'error', $error);
        }
        ?>
        <p><label for="first_name">First name</label><input name="first_name" id="first_name" value="<?= $user->first_name ?>"/></p>
        <p><label for="last_name">Last name</label><input name="last_name" id="last_name" value="<?= $user->last_name ?>"/></p>
        <p><label for="email">E-mail</label><input type="email" name="email" id="email" value="<?= $user->email ?>"/></p>
        <p><label for="password">Password</label><input type="password" name="password" id="password" value=""/></p>
        <p><label for="password_confirmation">Confirm password</label><input type="password" name="password_confirmation" id="password_confirmation" value=""/></p>
        <p><input type="submit" name="commit" value="Enter"/></p>
      </fieldset>
    </form>
  </div>
</body>
</html>
