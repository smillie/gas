<?php require 'header.php'; ?>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
							<li class="nav-header"><img src="http://www.gravatar.com/avatar/<?php echo $avatar; ?>?s=20&d=mm" /> <?php echo $user_get[0]["uid"][0]; ?></li>
              <li><a href="index.php">Details</a></li>
              <li class="active"><a href="password.php">Change Password</a></li>
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
							    <legend>Change Password</legend>
									<div class="control-group">
							      <label class="control-label" for="oldpw">Old Password</label>
							      <div class="controls">
							        <input type="password" class="input-xlarge" id="oldpw">
							      </div>
							    </div>
									<div class="control-group">
							      <label class="control-label" for="newpw">New Password</label>
							      <div class="controls">
						        <input type="password" class="input-xlarge" id="newpw">
							      </div>
							    </div>
									<div class="control-group">
							      <label class="control-label" for="confirmpw">Confirm Password</label>
							      <div class="controls">
						        <input type="password" class="input-xlarge" id="confirmpw">
							      </div>
							    </div>
									<div class="form-actions">
										<button type="submit" class="btn btn-primary">Update Password</button>
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