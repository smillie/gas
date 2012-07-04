<?php require 'ldapconnect.php'; ?>
<?php
  if (isset($_POST['oldpw'])) {
    ldap_unbind();
    if (ldap_bind($con, $userdn, $_POST['oldpw'])===true) {
      if ($_POST['confirmpw'] == $_POST['newpw']) {
        $password="{MD5}".base64_encode(pack("H*",md5($_POST['newpw'])));
        $entry=array();
        $entry['userpassword']=$password;
        $_SESSION['password']=$_POST['newpw'];
        ldap_modify($con,$userdn,$entry);
      } else {
        $error = "<strong>Error:</strong> Password do not match.";
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
              <form class="form-horizontal" action="password.php" method="post">
                <fieldset>
                  <legend>Change Password</legend>
                  <div class="control-group">
                    <?php
                      if (isset($error)) {
                        echo '<div class="alert alert-error">';
                          echo $error;
                        echo '</div>';
                      }
                    ?>
                    <label class="control-label" for="oldpw">Old Password</label>
                    <div class="controls">
                      <input type="password" class="input-xlarge" name="oldpw" id="oldpw">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="newpw">New Password</label>
                    <div class="controls">
                    <input type="password" class="input-xlarge" name="newpw" id="newpw">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="confirmpw">Confirm Password</label>
                    <div class="controls">
                    <input type="password" class="input-xlarge" name="confirmpw" id="confirmpw">
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
      </div><!--/row-->

<?php require 'footer.php'; ?>