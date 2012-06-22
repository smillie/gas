<?php require 'header.php'; ?>

        <div class="span10">
          <!-- <div class="page-header">
            <h1>Your GeekSoc Account:</h1>
          </div> -->
          <div class="row-fluid">
            <div class="span4">
							<form class="form-horizontal">
							  <fieldset>
							    <legend>SSH Keys</legend>
							    <!-- <div class="control-group">
							      <label class="control-label" for="uid"><button class="btn btn-danger" href="#">Delete</button></label>
							      <div class="controls">
							        <textarea class="input-xlarge" id="textarea" rows="3" placeholder="<?php echo $user_get[0]["sshpublickey"][0]; ?>" disabled="disabled"></textarea>
							      </div>
							    </div> -->
							
									<?php
										foreach (array_slice($user_get[0]["sshpublickey"], 1) as $key) {
											echo '<div class="control-group">';
											echo '<label class="control-label" for="uid"><button class="btn btn-danger" href="#">Delete</button></label>';
											echo '<div class="controls">';
											echo '<textarea class="input-xlarge" id="textarea" rows="7" disabled="disabled">'.$key.'</textarea>';
											echo '</div>';
											echo '</div>';
										}
									?>
							
							    <div class="control-group">
							      <label class="control-label" for="uid">Add Key</label>
							      <div class="controls">
							        <textarea class="input-xlarge" id="textarea" rows="7"></textarea>
							      </div>
							    </div>
							
									<div class="form-actions">
										<button type="submit" class="btn btn-primary">Update Keys</button>
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
