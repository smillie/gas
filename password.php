<?php require 'ldapconnect.php'; ?>
<?php

    $pageTitle = " - Change Password";    

  if (isset($_POST['oldpw'])) {
    if (ldap_bind($con, $userdn, $_POST['oldpw'])===true) {
      if ($_POST['confirmpw'] == $_POST['newpw']) {
        mt_srand((double)microtime()*1000000);
        $salt = pack("CCCCCCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand());
        $password = "{SSHA}" . base64_encode( sha1( $_POST['newpw'] . $salt, true) . $salt );
        $entry=array();
        $entry['userpassword']=$password;
        $_SESSION['password']=$_POST['newpw'];
        ldap_modify($con,$userdn,$entry);
        $success = "Password updated successfully.";
      } else {
        $error = "<strong>Error:</strong> Passwords do not match.";
      }
    } else {
      $error = "<strong>Error:</strong> Password incorrect.";
    }
  }
?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

        <div class="span10">
          <div class="row-fluid">
            <div class="span4">
              <form id="form" class="form-horizontal" action="password.php" method="post">
                <fieldset>
                  <legend>Change Password</legend>
                  <div class="control-group">
                    <?php if (isset($error)) : ?>
                      <div class="alert alert-error">
                        <?php echo "$error"; ?>
                      </div>
                    <?php elseif (isset($success)) : ?>
                      <div class="alert alert-success">
                        <?php echo "$success"; ?>
                      </div>
                    <?php endif; ?>
                    <label class="control-label" for="oldpw">Old Password</label>
                    <div class="controls">
                      <input type="password" class="input-xlarge required" name="oldpw" id="oldpw">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="newpw">New Password</label>
                    <div class="controls">
                    <input type="password" class="input-xlarge required" name="newpw" id="newpw" minlength="8">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="confirmpw">Confirm Password</label>
                    <div class="controls">
                    <input type="password" class="input-xlarge required" name="confirmpw" id="confirmpw">
                    </div>
                  </div>
                  <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                  </div>
                </fieldset>
              </form>
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->

<?php require 'footer.php'; ?>
