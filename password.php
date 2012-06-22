<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

        <div class="span10">
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
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->

<?php require 'footer.php'; ?>