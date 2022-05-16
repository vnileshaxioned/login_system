<?php
require_once('user_script.php');
include_once('includes/header.php');
?>
    <h3>User Profile Edit</h3>
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
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <input type="hidden" name="user_id" value="<?php echo $detail['id']; ?>">
        </div>
        <div class="form-group">
          <img src="upload/<?php echo $_SESSION['profile_image']; ?>" alt="<?php echo $_SESSION['profile_image']; ?>">
        </div>
        <div class="form-group">
          Select Profile Image: <input type="file" name="file">
          <span> <?php echo $check_file; ?></span>
        </div>
        <div class="form-group">
          <input type="submit" name="profile_image_update" value="Submit" />
        </div>
      </form>
      <?php } } ?>
    </div>
  </body>
</html>