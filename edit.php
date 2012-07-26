<?php require 'ldapconnect.php'; ?>
<?php
    if (!isUserInGroup($con, $user, "gsag")) {
        header( 'Location: index.php' );
    }

    if (isset($_POST['reset'])) {
        $u = $_GET['user'];
        $search = ldap_search($con, $dn, "(uid=$u)");
        $result = ldap_get_entries($con, $search);
        $pass = generatePassword(); 
        mt_srand((double)microtime()*1000000);
        $salt = pack("CCCCCCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand());
        $hashedpass = "{SSHA}" . base64_encode( sha1( $pass . $salt, true) . $salt );
        $entry['userpassword'] = $hashedpass;
        ldap_modify($con,"uid=".$u.",".$dn,$entry);
        $success = "Password for $u reset to '$pass'.";

        $mailmessage = <<<EOT
Your GeekSoc password has been reset by an administrator.

Username: $u
New Password: $pass

GeekSoc
http://www.geeksoc.org/
EOT;
        mail($result[0]['mail'][0], "[GeekSoc] Your password has been reset", $mailmessage, "From: support@geeksoc.org");

        $ircmessage = "#gsag Password reset for $u (by $user)";
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($sock, "irc.geeksoc.org", 5050);
        socket_write($sock, $ircmessage, strlen($ircmessage));
        socket_close($sock);

    }
    if (isset($_POST['delete'])) {
        $u = $_GET['user'];
        ldap_delete($con,"uid=".$u.",".$dn);
        if (ldap_error($con) != "Success") {
            $error = ldap_error($con);
        } else {
            $success = "User $u has been deleted.";
        }

        $groups = getGroupsForUser($con, $u);
        foreach ($groups as $g) {
            $attr['memberUid'] = $u;
            ldap_mod_del($con, "cn=" . $g . ",ou=groups,dc=geeksoc,dc=org", $attr);
        }

        $ircmessage = "#gsag Account deleted: $u (by $user)";
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($sock, "irc.geeksoc.org", 5050);
        socket_write($sock, $ircmessage, strlen($ircmessage));
        socket_close($sock);


    }
    else if (isset($_GET['user'])) {
        $u = $_GET['user'];
        $search = ldap_search($con, $dn, "(uid=$u)");
        $result = ldap_get_entries($con, $search);
        if (ldap_count_entries($con, $search) == 0) {
            $error = "No such user.";
        }
    }
    else {
        //header( 'Location: listusers.php' );
    }

    if (isset($_POST['update'])) {
        $entry['cn'] = $_POST['cn'];
        if ($entry['studentnumber'] != NULL ) {
            $entry['studentnumber'] = $_POST['studentnumber'];
        }
        $entry['mail'] = $_POST['email'];
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
        $edate = strtotime($_POST['expiry']);
        $entry['shadowexpire'] = intval($edate/(60*60*24))+1;
        if ($result[0]['haspaid'][0] != NULL ) {
            switch ($_POST['hasPaid']) {
                case "yes":
                    $entry['hasPaid'] = "TRUE";
                    break;
                case "no":
                    $entry['hasPaid'] = "FALSE";
                    break;
            }
        }
        if ($_POST['notes'] == NULL) {
            $notes['notes'] =  $result[0]['notes'][0];
            ldap_mod_del($con,"uid=".$u.",".$dn,$notes);
        } else {
            $entry['notes'] = $_POST['notes'];
        }
        
        ldap_modify($con,"uid=".$u.",".$dn,$entry);
        if (ldap_error($con) != "Success") {
            $error = ldap_error($con);
        }
        $success = "Details updated successfully.";

        $search = ldap_search($con, $dn, "(uid=$u)");
        $result = ldap_get_entries($con, $search);

    }
    $pageTitle = ' - Edit User: '.$u;
?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>
        <div class="span10">
          <div class="row">
            <div class="span5">
            <?php if (isset($_GET['user']) && !isset($_POST['delete'])) : ?>
              <form id="form" class="form-horizontal" action="edit.php?user=<?php echo $u; ?>" method="post">
                <fieldset>
                  <legend>Edit User</legend>
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
                    <label class="control-label" for="uid">Account Name</label>
                    <div class="controls">
                      <span class="input-xlarge uneditable-input"><?php echo $result[0]["uid"][0]; ?></span>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="cn">Full Name</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge required" name="cn" id="cn" value="<?php echo $result[0]["cn"][0]; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="studentNumber">Student Number</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge digits" name="studentnumber" id="studentnumber" value="<?php echo $result[0]["studentnumber"][0]; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge required email" name="email" id="email" value="<?php echo $result[0]["mail"][0]; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="loginShell">Login Shell</label>
                      <div class="controls">
                        <select id="loginShell" name="loginShell">
                          <?php $shell=$result[0]["loginshell"][0]?>
                          <option <?php echo($shell == "/bin/bash"?' selected="selected"':null) ?>>/bin/bash</option>
                          <option <?php echo($shell == "/bin/tcsh"?' selected="selected"':null) ?>>/bin/tcsh</option>
                          <option <?php echo($shell == "/bin/zsh"?' selected="selected"':null) ?>>/bin/zsh</option>
                        </select>
                      </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Groups</label>
                        <div class="controls">
                            <span class="uneditable-input input-xlarge">
                                <?php foreach(getGroupsForUser($con,$u) as $g){ echo "<a href='editgroup.php?group=$g'>$g</a> "; } ?>
                            </span>
                        </div>
                    </div>
              
                    <div class="control-group">
                        <label class="control-label" for="status">Account Status</label>
                        <div class="controls">
                            <span class="input-xlarge uneditable-input"><?php echo getStatus($result[0]['shadowexpire'][0], $result[0]['haspaid'][0]); ?></span>
                        </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Expiry</label>
                    <div class="controls">
                      <?php 
                            $expiry =  intval($result[0]['shadowexpire'][0])*(60*60*24);
                            $edate = date("Y-m-d", $expiry);
                        ?>
                        <input type="text" class="input-xlarge required dateISO" name="expiry" id="expiry" value="<?php echo $edate; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Paid?</label>
                    <div class="controls">
                      <label class="radio inline">
                        <input type="radio" name="hasPaid" id="optionsRadios1" value="yes" <?php echo($result[0]['haspaid'][0]== "TRUE"?' checked':null) ?>> Yes
                      </label>
                      <label class="radio inline">
                      <input type="radio" name="hasPaid" id="optionsRadios2" value="no" <?php echo($result[0]['haspaid'][0]== "FALSE"?' checked':null) ?>> No
                      </label>
                    </div>
                  </div>
                <div class="control-group">
                    <label class="control-label" for="notes">Notes</label>
                    <div class="controls">
                    <textarea class="input-xlarge" id="notes" name="notes" rows="3"><?php echo $result[0]['notes'][0]; ?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="notes">Password</label>
                    <div class="controls">
                    <button name="reset" type="submit" class="btn btn-small" >Reset Password</button>
                    </div>
                </div>
                <div class="form-actions">
                    <div id="deleteConfromModal" class="modal hide ">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h3>Are you sure?</h3>
                                </div>
                                <div class="modal-body">
                                <p>This will immediately delete <?php echo $result[0]['cn'][0]; ?>'s account from the LDAP directory.</p>
                                <p><strong>Note:</strong> The home directory will not be deleted.</p>
                                </div>
                                <div class="modal-footer">
                                  <a href="#" class="btn" data-dismiss="modal" >Cancel</a>
                                  <button name="delete" type="submit" class="btn btn-danger" ><i class="icon-trash icon-white"></i> Confirm Delete</button>
                                </div>
                              </div>
                              <a data-toggle="modal" href="#deleteConfromModal" class="btn btn-danger"><i class="icon-trash icon-white"></i> Delete</a>
                    <button type="submit" class="btn btn-primary" name="update" >Update Details</button>
                  </div>
                </fieldset>
              </form>
              <?php else : ?>
                   <fieldset>
                     <legend>Edit User</legend>
                     <div class="control-group">
                    <form class="well form-search" action="listusers.php" method='GET'>
                        <input type="text" name="search" class="input-xlarge search-query">
                        <button type="submit" class="btn">Search</button>
                    </form>
                    </div>
              <?php endif; ?>
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->

<?php require 'footer.php'; ?>
