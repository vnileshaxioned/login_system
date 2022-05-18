<?php
require_once('admin_script.php');
include_once('../includes/admin_header.php');
notLogin('id');
?>
    <h3>Users List</h3>
    <?php if (isset($_SESSION['user_updated'])) { ?>
      <h3><?php echo $_SESSION['user_updated']; ?></h3>
      <?php } ?>
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
            unset($_SESSION['user_updated']);
            $user_detail = fetchUser('users', $conn);
            if ($user_detail->num_rows > 0) {
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
              <a href="edit_user.php?id=<?php echo $detail['id']; ?>" class="button edit">Edit</a>
              <a href="users_list.php?delete_id=<?php echo $detail['id']; ?>" class="button delete">Delete</a>
            </td>
            <?php
                }
              } else {
            ?>
            <td colspan="7">No user data found</td>
            <?php } ?>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>