<?php require 'ldapconnect.php'; ?>
<?php
if (!isUserInGroup($con, $user, "gsag")) {
    header( 'Location: index.php' );
}
   
    $pageTitle = ' - Add User';    

    if (isset($_POST['firstname']) && strlen($_POST['uid']) > 2) {
        $uid = $_POST['uid'];
        $first = ucfirst($_POST['firstname']);
        $last = ucfirst($_POST['lastname']);
        $stuno = $_POST['studentnumber'];
        $email = $_POST['email'];
        
        $returnMessage = addUser($uid, $first, $last, $stuno, $email);
        if (substr($returnMessage, 0, 1) =='S') {
            $success = substr($returnMessage, 2);
        } else {
            $error = $returnMessage;
        }

   }
?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

        <div class="span10">
          <div class="row">
            <div class="span5">
              <form class="form-horizontal" id="form" action="adduser.php" method="post">
                <fieldset>
                  <legend>Add User</legend>
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
                    <label class="control-label" for="firstname">First Name</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge required" name="firstname" id="firstname">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="lastname">Last Name</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge required" name="lastname" id="lastname">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="uid">Account Name</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge required" name="uid" id="uid" minlength="3">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="studentNumber">Student Number</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge digits" name="studentnumber" id="studentnumber" >
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge required email" name="email" id="email" >
                    </div>
                  </div>
                  <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add User</button>
                  </div>
                </fieldset>
              </form>
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->

<?php require 'footer.php'; ?>
