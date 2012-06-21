<?php require 'header.php'; ?>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header"><img src="http://www.gravatar.com/avatar/<?php echo $avatar; ?>?s=20&d=mm" /> <?php echo $user_get[0]["uid"][0]; ?></li>
              <li class="active"><a href="index.php">Details</a></li>
              <li><a href="password.php">Change Password</a></li>
              <li><a href="sshkeys.php">SSH Keys</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span10">
          <!-- <div class="page-header">
            <h1>Your GeekSoc Account:</h1>
          </div> -->
          <div class="row-fluid">
            <div class="span4">
							<form class="form-horizontal">
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
							        <input type="text" class="input-xlarge" id="cn" value="<?php echo $user_get[0]["cn"][0]; ?>">
							      </div>
							    </div>
									<div class="control-group">
							      <label class="control-label" for="studentNumber">Student Number</label>
							      <div class="controls">
						        <input type="text" class="input-xlarge" id="studentNumber" value="<?php echo $user_get[0]["studentnumber"][0]; ?>">
							      </div>
							    </div>
									<div class="control-group">
							      <label class="control-label" for="email">Email</label>
							      <div class="controls">
						        <input type="text" class="input-xlarge" id="email" value="<?php echo $user_get[0]["mail"][0]; ?>">
							      </div>
							    </div>
									<div class="control-group">
							      <label class="control-label" for="loginShell">Login Shell</label>
					            <div class="controls">
					              <select id="loginShell">
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
													$stat_icon = "icon-remove";
												}
												if ($i == 1) {
												  $status = "Administratively Disabled";
													$stat_icon = "icon-remove";
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
							
              <!-- <p><a class="btn" href="#">View details &raquo;</a></p> -->
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <?php require 'footer.php'; ?>