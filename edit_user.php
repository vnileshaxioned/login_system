<?php
require_once('user_script.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Details</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <div class="container">
      <div class="nav">
        <div class="left-nav">
          <h3>User Edit</h3>
        </div>
        <div class="right-nav">
          <h3 class="menu"><?php echo $_SESSION['email']; ?></h3>
          <img src="upload/<?php echo $_SESSION['profile_image']; ?>" class="menu" alt="<?php echo $_SESSION['profile_image']; ?>">
          <form class="menu" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="submit" name="user_logout" value="Logout" />
          </form>
        </div>
      </div>
        <?php
          if (isset($_SESSION['success'])) {
            echo $_SESSION['success'];
            unset($_SESSION['success']);
          }
        ?>
      </h3>
      <?php
        if (isset($_POST['user_edit'])) {
          $id = validateInput($_POST['id']);
          $query = "SELECT * FROM users WHERE id = $id";
          $user_detail = $conn->query($query);
          if ($user_detail > 0) {
            while ($detail = $user_detail->fetch_assoc()) {
      ?>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
          Email: <input type="text" name="email" class="form-control" value="<?php echo $detail['email']; ?>" placeholder="Enter Email" />
          <span> * <?php echo $email_error; ?></span>
        </div>
        <div class="form-group">
          Password: <input type="password" name="pass" class="form-control" placeholder="Enter Password" />
          <span> * <?php echo $pass_error; ?></span>
        </div>
        <div class="form-group">
          <input type="submit" name="user_update" value="Submit" />
        </div>
      </form>
      <?php } } } else {
          header('Location: dashboard.php');
      } ?>
    </div>
  </body>
</html>