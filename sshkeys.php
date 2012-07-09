<?php require 'ldapconnect.php'; ?>
<?php
    if (isset($_POST['delete'])) {
        $userinfo["sshpublickey"] = str_replace("\r\n", "\n", $_POST['delete']);
        ldap_mod_del($con, $userdn, $userinfo);
        echo ldap_error($con);
        header( 'Location: sshkeys.php' );
    }
?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

        <div class="span10">
          <div class="row-fluid">
            <div class="span4">
              <form class="form-horizontal" action="sshkeys.php" method="post">
                <fieldset>
                  <legend>SSH Keys</legend>
              
                  <?php
                    foreach (array_slice($user_get[0]["sshpublickey"], 1) as $key) {
                      echo '<div class="control-group">';
                      echo '<label class="control-label" for="uid"><button name="delete" value="'.$key.'"type="submit" class="btn btn-danger" ><i class="icon-trash icon-white"></i> Delete</button></label>';
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
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->

<?php require 'footer.php'; ?>
