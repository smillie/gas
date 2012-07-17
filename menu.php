    <div class="span2">
      <div class="well sidebar-nav">
        <ul class="nav nav-list">
          <li class="nav-header"><img src="http://www.gravatar.com/avatar/<?php echo $avatar; ?>?s=20&d=mm" /> <?php echo $user_get[0]["uid"][0]; ?></li>
          <li <?php echo((strpos($_SERVER['PHP_SELF'], 'details.php'))? 'class="active"':null) ?>><a href="details.php"><i class="icon-user"></i> Details</a></li>
          <li <?php echo((strpos($_SERVER['PHP_SELF'], 'password.php'))? 'class="active"':null) ?>><a href="password.php"><i class="icon-lock"></i> Change Password</a></li>
          <li <?php echo((strpos($_SERVER['PHP_SELF'], 'sshkeys.php'))? 'class="active"':null) ?>><a href="sshkeys.php"><i class="icon-file"></i> SSH Keys</a></li>
          <?php if (isUserInGroup($con, $user, "gsag")) : ?>
          <li class="nav-header">User Administration</li>
          <li <?php echo((strpos($_SERVER['PHP_SELF'], 'adduser.php'))? 'class="active"':null) ?>><a href="adduser.php"><i class="icon-plus"></i> Add User</a></li>
          <li <?php echo((strpos($_SERVER['PHP_SELF'], 'edit.php'))? 'class="active"':null) ?>><a href="edit.php"><i class="icon-pencil"></i> Edit User</a></li>
          <li <?php echo((strpos($_SERVER['PHP_SELF'], 'listusers.php'))? 'class="active"':null) ?>><a href="listusers.php"><i class="icon-th-list"></i> List Users</a></li>
          <?php endif; ?>
        </ul>
      </div><!--/.well -->
    </div><!--/span-->
