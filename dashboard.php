<?php
require_once('user_script.php');
include_once('includes/header.php');
notLogin('id');
?>
<h3>User Profile</h3>
    <h3><?php echo $message; ?></h3>
    <?php if (isset($_SESSION['user_updated'])) { ?>
      <h3><?php echo $_SESSION['user_updated']; ?></h3>
    <?php unset($_SESSION['user_updated']); } ?>
      <?php
        $id == $_SESSION['id'];
        $detail = fetchUser('users', $conn, 'i', 'id', $id);
          if ($detail > 0){
      ?>
        <p>Name: <?php echo $detail['name']; ?></p>
        <p>Email: <?php echo $detail['email']; ?></p>
        <p>Phone Number: <?php echo $detail['phone_number']; ?></p>
        <p>Gender: <?php echo $detail['gender']; ?></p>
        <a href="edit_user.php?id=<?php echo $detail['id']; ?>" class="button edit">Edit Profile</a>
        <a href="edit_password.php?id=<?php echo $detail['id']; ?>" class="button edit">Edit Password</a>
        </form>
        <?php } ?>
    </div>
  </body>
</html>