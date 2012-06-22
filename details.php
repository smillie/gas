<?php require 'ldapconnect.php'; ?>
<?php
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
		
		$user_search = ldap_search($con, $dn, "(uid=$user)");
		$user_get = ldap_get_entries($con, $user_search); 

		$avatar = md5( strtolower( trim($user_get[0]["mail"][0] ) ) );
	}
?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>
				<div class="span10">
          <div class="row-fluid">
            <div class="span4">
							<form class="form-horizontal" action="details.php" method="post">
							  <fieldset>
							    <legend>Account Details</legend>
							    <div class="control-group">
							      <label class="control-label" for="uid">Account Name</label>
							      <div class="controls">
							        <span class="input-xlarge uneditable-input"><?php echo $user_get[0]["uid"][0]; ?></span>
							      </div>
							    </div>
									<div class="control-group">
							      <label class="control-label" for="cn">Full Name</label>
							      <div class="controls">
							        <input type="text" class="input-xlarge" name="cn" id="cn" value="<?php echo $user_get[0]["cn"][0]; ?>">
							      </div>
							    </div>
									<div class="control-group">
							      <label class="control-label" for="studentNumber">Student Number</label>
							      <div class="controls">
						        <input type="text" class="input-xlarge" name="studentnumber" id="studentnumber" value="<?php echo $user_get[0]["studentnumber"][0]; ?>">
							      </div>
							    </div>
									<div class="control-group">
							      <label class="control-label" for="email">Email</label>
							      <div class="controls">
						        <input type="text" class="input-xlarge" name="email" id="email" value="<?php echo $user_get[0]["mail"][0]; ?>">
							      </div>
							    </div>
									<div class="control-group">
							      <label class="control-label" for="loginShell">Login Shell</label>
					            <div class="controls">
					              <select id="loginShell" name="loginShell">
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
											<?php
												$day = intval(time()/(60*60*24));
												$i = (int)$user_get[0]["shadowexpire"][0];
												if (!isset($user_get[0]["shadowexpire"][0])) $i=99999;
												$status = "Active";
												$stat_icon = "icon-ok";
												if ($user_get[0]["haspaid"][0] == "FALSE") {
												  $status = "Not Paid";
													$stat_icon = "icon-exclamation-sign";
												} 
												if ($i <= ($day+60)) {
												  $status = "Expiring";
													$stat_icon = "icon-exclamation-sign";
												}
												if ($i <= $day) {
												  $status = "Expired";
													$stat_icon = "icon-ban-circle";
												}
												if ($i == 1) {
												  $status = "Administratively Disabled";
													$stat_icon = "icon-ban-circle";
												}
												echo '<span class="input-xlarge uneditable-input"><i class="'.$stat_icon.'"></i> '.$status.'</span>'
											?>
							      </div>
							    </div>
									<div class="form-actions">
										<button type="submit" class="btn btn-primary">Update Details</button>
									</div>
							  </fieldset>
							</form>
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->

<?php require 'footer.php'; ?>