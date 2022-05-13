<?php
require_once('user_script.php');
notLogin('email');
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
          <h3>User Details</h3>
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
            $query = "SELECT * FROM users WHERE email = '".$_SESSION['email']."'";
            $user_detail = $conn->query($query);
            if ($user_detail > 0) {
              while ($detail = $user_detail->fetch_assoc()) {
          ?>
            <p>Name: <?php echo $detail['name']; ?></p>
            <p>Email: <?php echo $detail['email']; ?></p>
            <p>Phone Number: <?php echo $detail['phone_number']; ?></p>
            <p>Gender: <?php echo $detail['gender']; ?></p>
            <form action="edit_user.php" method="post">
              <input type="hidden" name="user_id" value="<?php echo $detail['id']; ?>">
              <input type="submit" name="user_edit" value="Edit">
              <input type="submit" name="user_delete" value="Delete">
            </form>
            <?php } } ?>
    </div>
  </body>
</html>