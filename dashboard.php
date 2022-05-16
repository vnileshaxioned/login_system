<?php
require_once('user_script.php');
include_once('includes/header.php');
notLogin('email');
?>
<h3>User Profile</h3>
  <h3>
    <?php 
      echo $message;
      if (isset($_SESSION['user_updated'])) {
        echo $_SESSION['user_updated'];
        unset($_SESSION['user_updated']);
      }
    ?>
  </h3>
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
        </form>
        <form action="edit_user_password.php" method="post">
          <input type="hidden" name="user_id" value="<?php echo $detail['id']; ?>">
          <input type="submit" name="password_edit" value="Update Password">
        </form>
        <form action="edit_user_profile.php" method="post">
          <input type="hidden" name="user_id" value="<?php echo $detail['id']; ?>">
          <input type="submit" name="profile_image_edit" value="Update Profile Image">
        </form>
        </form>
        <?php } } ?>
    </div>
  </body>
</html>