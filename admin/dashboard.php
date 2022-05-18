<?php
require_once('admin_script.php');
include_once('../includes/admin_header.php');
notLogin('id');
?>
    <h3>Users List</h3>
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