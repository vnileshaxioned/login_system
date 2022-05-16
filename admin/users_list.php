<?php
require_once('admin_script.php');
include_once('../includes/admin_header.php');
notLogin('email');
?>
    <h3>Users List</h3>
    <?php
      echo $message;
      if (isset($_SESSION["user_deleted"]) || isset($_SESSION['user_updated'])) {
        echo $_SESSION["user_deleted"].$_SESSION['user_updated'];
        unset($_SESSION["user_deleted"]);
        unset($_SESSION['user_updated']);
      }
    ?>
      <table>
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Gender</th>
            <th>Profile Image</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            
            $user_detail = fetchUser('users', $conn);
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
                src="../upload/<?php echo $detail['profile_image']; ?>"
                alt="<?php echo $detail['profile_image']; ?>"
              />
              <?php 
                } else {
                  echo "No image";
                }
              ?>
            </td>
            <td>
              <form action="edit_user.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $detail['id']; ?>">
                <input type="submit" name="user_edit" value="Edit">
              </form>
              <form action="admin_script.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $detail['id']; ?>">
                <input type="submit" name="user_delete" value="Delete">
              </form>
            </td>
            <?php
                }
              } else {
            ?>
            <td colspan="6">No user data found</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </body>
</html>