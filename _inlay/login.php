<?php
require 'config.inc.php';
require('models/session.php');
require('models/user.php');
$error = '';
$session = new Session();
if(present('email', $_POST)){
  if($user = $session->authenticate($_POST['email'], $_POST['password'])){
    $_SESSION['current_user'] = $user->id;
    if(present('next', $_SESSION)){
      $next = $_SESSION['next'];
      unset($_SESSION['next']);
      header('Location: ' . $next);
    }else{
      header('Location: index.html');
    }
    exit;
  }else{
    $error = '<li>Invalid username or password</li>';
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
    <form action="" method="post" accept-charset="utf-8">
      <h1 id="please_log_in">Please Log In</h1>
      <fieldset>
        <?php
        if(!!$error){
          printf($flash, 'error', $error);
        }
        ?>
        <p><label for="email">E-mail</label><input type="email" name="email" id="email" value="" autofocus="autofocus"/></p>
        <p><label for="password">Password</label><input type="password" name="password" id="password" value=""/></p>
        <p><input type="submit" value="Enter"/></p>
      </fieldset>
    </form>
  </div>
</body>
</html>
