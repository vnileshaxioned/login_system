<?php
require_once('user_script.php');
include_once('includes/header.php');
?>
    <h3>User Password Edit</h3>
      <h3>
        <?php
          echo $message;
          if (isset($_SESSION['success'])) {
            echo $_SESSION['success'];
            unset($_SESSION['success']);
          }
        ?>
      </h3>
      <?php
          $id = validateInput($_POST['user_id']);
          $user_detail = fetchUser('users', $conn, 'i', 'id', $id);
          if ($user_detail > 0) {
            while ($detail = $user_detail->fetch_assoc()) {
      ?>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
          <input type="hidden" name="user_id" value="<?php echo $detail['id']; ?>">
        </div>
        <div class="form-group">
          Current Password: <input type="password" name="current_password" class="form-control" placeholder="Enter Current Password" />
          <span> * <?php echo $current_password_error ? $current_password_error : $check_password ; ?></span>
        </div>
        <div class="form-group">
          New Password: <input type="password" name="new_password" class="form-control" placeholder="Enter New Password" />
          <span> * <?php echo $new_password_error ? $new_password_error : $check_password ; ?></span>
        </div>
        <div class="form-group">
          Confirm Password: <input type="password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password" />
          <span> * <?php echo $confirm_password_error ? $confirm_password_error : $check_password ; ?></span>
        </div>
        <div class="form-group">
          <input type="submit" name="password_update" value="Submit" />
        </div>
      </form>
      <?php } } ?>
    </div>
  </body>
</html>