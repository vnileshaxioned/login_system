<?php
require_once('user_script.php');
include_once('includes/header.php');
?>
    <h3>User Password Edit</h3>
        <?php if (isset($_SESSION['error'])) { ?>
          <h3><?php echo $_SESSION['error']; ?></h3>
        <?php 
          }
          $id = $_GET['id'];
          if ($id == $_SESSION['id']) {
          unset($_SESSION['error']);
        ?>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
          <input type="hidden" name="user_id" value="<?php echo $_GET['id']; ?>">
        </div>
        <div class="form-group">
          Current Password: <input type="password" name="current_password" class="form-control" placeholder="Enter Current Password" />
          <span> * <?php echo $_GET['current']; ?></span>
        </div>
        <div class="form-group">
          New Password: <input type="password" name="new_password" class="form-control" placeholder="Enter New Password" />
          <span> * <?php echo $_GET['new']; ?></span>
        </div>
        <div class="form-group">
          Confirm Password: <input type="password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password" />
          <span> * <?php echo $_GET['confirm']; ?></span>
        </div>
        <div class="form-group">
          <input type="submit" name="password_update" value="Submit" />
        </div>
      </form>
      <?php 
        } else {
      ?>
        <h3>Please do not change anything in URL</h3>
      <?php } ?>
    </div>
  </body>
</html>