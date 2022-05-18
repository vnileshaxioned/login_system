<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Details</title>
    <link rel="stylesheet" href="../css/style.css" />
  </head>
  <body>
    <div class="container">
      <div class="nav">
        <div class="left-nav">
          <h3><a href="dashboard.php">User Details</a></h3>
        </div>
        <div class="right-nav">
          <?php
            $id = $_SESSION['id'];
            $detail = fetchUser('admin', $conn, 'i', 'id', $id);
            if ($detail > 0){
          ?>
          <div class="menu"><a href="users_list.php">Users List</a></div>
          <h3 class="menu"><?php echo $detail['email']; ?></h3>
          <img src="../upload/<?php echo $detail['profile_image']; ?>" class="menu" alt="<?php echo $detail['profile_image']; ?>">
          <form class="menu" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="submit" name="admin_logout" value="Logout" />
          </form>
          <?php } ?>
        </div>
      </div>