<?php
require_once('admin_script.php');
include_once('../includes/admin_header.php');
notLogin('id');
?>
    <h3>User Profile Edit</h3>
      <?php if (isset($_SESSION['error'])) { ?>
        <h3><?php echo $_SESSION['error']; ?></h3>
      <?php } ?>
      <?php
        $id = $_GET['id'];
        $detail = fetchUser('users', $conn, 'i', 'id', $id);
        if ($detail > 0){
      ?>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <input type="hidden" name="user_id" value="<?php echo $detail['id']; ?>">
        </div>
        <div class="form-group">
          Name: <input type="text" name="name" class="form-control" value="<?php echo $detail['name']; ?>" placeholder="Enter Name" />
          <span> * <?php echo $_GET['name']; ?></span>
        </div>
        <div class="form-group">
          Email: <input type="text" name="email" class="form-control disabled" value="<?php echo $detail['email']; ?>" placeholder="Enter Email" readonly/>
          <span> * <?php echo $_GET['email']; ?></span>
        </div>
        <div class="form-group">
          Phone Number: <input type="text" name="phone_number" class="form-control" value="<?php echo $detail['phone_number']; ?>" placeholder="Enter Phone Number" />
          <span> * <?php echo $_GET['phone_number']; ?></span>
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
          <span> * <?php echo $_GET['gender']; ?></span>
        </div>
        <div class="form-group">
          Profile Image: <input type="file" name="file">
          <span> <?php echo $_GET['file']; ?></span>
        </div>
        <div class="form-group">
          <input type="submit" name="user_update" value="Submit" />
        </div>
      </form>
      <?php } unset($_SESSION['error']); ?>
    </div>
  </body>
</html>