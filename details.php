<?php require 'ldapconnect.php'; ?>
<?php

    $pageTitle = " - Your Account";

  if (isset($_POST['cn'])) {
    $entry=array();
    $entry['cn']=$_POST['cn'];
    $entry['studentnumber']=$_POST['studentnumber'];
    $entry['mail']=$_POST['email'];
    switch ($_POST['loginShell']) {
      case "/bin/bash":
        $entry['loginshell']="/bin/bash";
        break;
      case "/bin/tcsh":
        $entry['loginshell']="/bin/tcsh";
        break;
      case "/bin/zsh":
        $entry['loginshell']="/bin/zsh";
        break;
    }
    
    
    ldap_modify($con,$userdn,$entry);
    $success = "Details updated successfully.";
    
    $user_search = ldap_search($con, $dn, "(uid=$user)");
    $user_get = ldap_get_entries($con, $user_search); 

    $avatar = md5( strtolower( trim($user_get[0]["mail"][0] ) ) );
  }
?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>
        <div class="span10">
          <div class="row">
            <div class="span5">
              <form class="form-horizontal" action="details.php" method="post" id="form">
                <fieldset>
                  <legend>Account Details</legend>
                    <?php if (isset($success)) : ?>
                    <div class="control-group">
                      <div class="alert alert-success">
                        <?php echo "$success"; ?>
                      </div>
                    </div>
                    <?php endif; ?>
                  <div class="control-group">
                    <label class="control-label" for="uid">Account Name</label>
                    <div class="controls">
                      <span class="input-xlarge uneditable-input"><?php echo $user_get[0]["uid"][0]; ?></span>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="cn">Full Name</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge required" name="cn" id="cn" value="<?php echo $user_get[0]["cn"][0]; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="studentNumber">Student Number</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge digits" name="studentnumber" id="studentnumber" value="<?php echo $user_get[0]["studentnumber"][0]; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge required email" name="email" id="email" value="<?php echo $user_get[0]["mail"][0]; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="loginShell">Login Shell</label>
                      <div class="controls">
                        <select id="loginShell" name="loginShell" class="required">
                          <?php $shell=$user_get[0]["loginshell"][0]?>
                          <option <?php echo($shell == "/bin/bash"?' selected="selected"':null) ?>>/bin/bash</option>
                          <option <?php echo($shell == "/bin/tcsh"?' selected="selected"':null) ?>>/bin/tcsh</option>
                          <option <?php echo($shell == "/bin/zsh"?' selected="selected"':null) ?>>/bin/zsh</option>
                        </select>
                      </div>
                    </div>              
                  <div class="control-group">
                    <label class="control-label" for="status">Account Status</label>
                    <div class="controls">
                        <span class="input-xlarge uneditable-input"><?php echo getStatus($user_get[0]['shadowexpire'][0], $user_get[0]['haspaid'][0]); ?></span>
                    </div>
                  </div>
                  <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Details</button>
                  </div>
                </fieldset>
              </form>
            </div><!--/span-->

						<div class="span4 offset1">
						<h3>Shell Access</h3>
						<p>The shell service can be accessed over SSH at <code>shell.geeksoc.org</code></p>
						<h3>IRC Access</h3>
						<p>Our IRC chat server is available at <code>irc.geeksoc.org</code> on port 6667</p>
						<h3>Email Account</h3>
						Email accounts are handled separately
						<p><button class="btn">Request Account</button></p>
						</div>
          </div><!--/row-->
        </div><!--/span-->

<?php require 'footer.php'; ?>
