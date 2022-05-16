<?php
require_once('admin_script.php');
isLogin('email');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Details</title>
    <link rel="stylesheet" href="../css/style.css" />
  </head>
  <body>
    <div class="container">
      <h3>
        <?php 
          echo $message;
          if (isset($_SESSION['success'])) {
            echo $_SESSION['success'];
            unset($_SESSION['success']);
          }
        ?>
      </h3>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
          Email:
          <input type="text" name="email" class="form-control"
            value="<?php
              if (isset($_COOKIE['email'])) {
                echo $_COOKIE['email'];
              } else {
                echo $email;
              }
              ?>" placeholder="Enter Email" />
          <span> * <?php echo $email_error; ?></span>
        </div>
        <div class="form-group">
          Password:
          <input type="password" name="pass" class="form-control"
          value="<?php
              if (isset($_COOKIE['password'])) {
                echo $_COOKIE['password'];
              }
              ?>" placeholder="Enter Password" />
          <span> * <?php echo $pass_error; ?></span>
        </div><div class="form-group">
          <input type="checkbox" name="remember" <?php if(isset($_COOKIE["email"])) { ?> checked <?php } ?>/> Remember me
        </div>
        <div class="form-group">
          <input type="submit" name="admin_login" value="Submit" />
        </div>
      </form>
    </div>
  </body>
</html>