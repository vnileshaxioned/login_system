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
      <table>
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Gender</th>
            <th>Profile Image</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $query = selectQuery('users');
            $user_detail = $conn->query($query);
            
            if ($user_detail > 0) {
              $id = 1;
              while ($detail = $user_detail->fetch_assoc()) {
          ?>
          <tr>
            <td><?php echo $id++; ?></td>
            <td><?php echo $detail['name']; ?></td>
            <td><?php echo $detail['email']; ?></td>
            <td><?php echo $detail['phone_number']; ?></td>
            <td><?php echo $detail['gender']; ?></td>
            <td>
              <?php
                if ($detail['profile_image']) {
              ?>
              <img
                src="upload/<?php echo $detail['profile_image']; ?>"
                alt="<?php echo $detail['profile_image']; ?>"
              />
              <?php 
                } else {
                  echo "No image";
                }
              ?>
            </td>
            <?php
                }
              } else {
            ?>
            <td colspan="6">No user data found</td>
          </tr>
          <?php } $conn->close(); ?>
        </tbody>
      </table>
    </div>
  </body>
</html>