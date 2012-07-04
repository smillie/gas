<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2">
      <div class="well sidebar-nav">
        <ul class="nav nav-list">
          <li class="nav-header"><img src="http://www.gravatar.com/avatar/<?php echo $avatar; ?>?s=20&d=mm" /> <?php echo $user_get[0]["uid"][0]; ?></li>
          <li <?php echo((strpos($_SERVER['PHP_SELF'], 'details.php'))? 'class="active"':null) ?>><a href="details.php">Details</a></li>
          <li <?php echo((strpos($_SERVER['PHP_SELF'], 'password.php'))? 'class="active"':null) ?>><a href="password.php">Change Password</a></li>
          <li <?php echo((strpos($_SERVER['PHP_SELF'], 'sshkeys.php'))? 'class="active"':null) ?>><a href="sshkeys.php">SSH Keys</a></li>
        </ul>
      </div><!--/.well -->
    </div><!--/span-->