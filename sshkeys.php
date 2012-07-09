<?php require 'ldapconnect.php'; ?>
<?php
    if (isset($_POST['delete'])) {
        $userinfo["sshpublickey"] = str_replace("\r\n", "\n", $_POST['delete']);
        ldap_mod_del($con, $userdn, $userinfo);
        $user_search = ldap_search($con, $dn, "(uid=$user)");
        $user_get = ldap_get_entries($con, $user_search);  
        $success = "Deleted key sucessfully.";
    } else if (isset($_FILES['uploadedkey'])) {
        $newkey = file_get_contents($_FILES['uploadedkey']['tmp_name']);
        $attrs['sshpublickey'] = $newkey;
        if (strlen($newkey) > 0) {
            ldap_mod_add($con, $userdn, $attrs);
            $success = "Added key successfully.";
        }
        $user_search = ldap_search($con, $dn, "(uid=$user)");
        $user_get = ldap_get_entries($con, $user_search);  
    }
?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

        <div class="span10">
          <div class="row-fluid">
            <div class="span4">
              <form enctype="multipart/form-data" class="form-horizontal" action="sshkeys.php" method="post">
                <fieldset>
                  <legend>SSH Keys</legend>
                    <?php if (isset($success)) : ?>
                    <div class="control-group">
                      <div class="alert alert-success">
                        <?php echo "$success"; ?>
                      </div>
                    </div>
                    <?php endif; ?>


                    <?php foreach (array_slice($user_get[0]["sshpublickey"], 1) as $key): ?>
                    <div class="control-group">
                        <label class="control-label" for="uid"><button name="delete" value="<?php echo $key; ?>"type="submit" class="btn btn-danger" ><i class="icon-trash icon-white"></i> Delete</button></label>
                        <div class="controls">
                            <textarea class="input-xlarge" id="textarea" rows="7" disabled="disabled"><?php echo $key; ?></textarea>
                        </div>
                    </div>
                    <?php endforeach; ?>
              
                  <div class="control-group">
                    <label class="control-label" for="uid">Add Key</label>
                    <div class="controls">
                         <input name="uploadedkey" type="file" />
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
