<?php
require_once('admin_script.php');
include_once('../includes/admin_header.php');
notLogin('email');
?>
    <h3>User Profile Edit</h3>
      <h3>
        <?php
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
          Name: <input type="text" name="name" class="form-control" value="<?php echo $detail['name']; ?>" placeholder="Enter Name" />
          <span> * <?php echo $name_error; ?></span>
        </div>
        <div class="form-group">
          Email: <input type="text" name="email" class="form-control" value="<?php echo $detail['email']; ?>" placeholder="Enter Email" />
          <span> * <?php echo $email_error; ?></span>
        </div>
        <div class="form-group">
          Phone Number: <input type="text" name="phone_number" class="form-control" value="<?php echo $detail['phone_number']; ?>" placeholder="Enter Phone Number" />
          <span> * <?php echo $phone_num_error; ?></span>
        </div>
        <div class="form-group">
          Gender:
          <?php
            $checked = "checked='checked'";
            $genders = array('Male', 'Female', 'Other');
            foreach ($genders as $gender_list) {
              if ($detail['gender'] == $gender_list) {
          ?>
          <input type="radio" name="gender" value="<?php echo $gender_list; ?>" <?php echo $checked; ?> />
          <?php echo $gender_list; ?>
          <?php } else { ?>
          <input type="radio" name="gender" value="<?php echo $gender_list; ?>"/>
          <?php echo $gender_list; ?>
          <?php } } ?>
          <span> * <?php echo $gender_error; ?></span>
        </div>
        <div class="form-group">
          <input type="submit" name="user_update" value="Submit" />
        </div>
      </form>
      <?php } } ?>
    </div>
  </body>
</html>