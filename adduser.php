<?php require 'ldapconnect.php'; ?>
<?php
if (!isUserInGroup($con, $user, "gsag")) {
    header( 'Location: index.php' );
}
   
    $pageTitle = ' - Add User';    

    if (isset($_POST['firstname']) && strlen($_POST['uid']) > 2) {
    //check if username exists
        $uid = $_POST['uid'];
        $first = $_POST['firstname'];
        $last = $_POST['lastname'];
        $stuno = $_POST['studentnumber'];
        $email = $_POST['email'];

        if (ldap_count_entries($con, ldap_search($con, $dn, "(uid=$uid)")) >= 1) {
            $error = "Username '$uid' already exists.";
        } else {

        //compute uid
            $users = ldap_get_entries($con, ldap_search($con, $dn, "(objectclass=posixaccount)"));
            $uidno = 10000;
            foreach($users as $u) {
                if ($u['uidnumber'][0] > $uidno) 
                    $uidno = $u['uidnumber'][0];
            }
            $uidno += 1;

        //compute expiry date
            $expiry = 15988;
            
        //generate password
            $pass = generatePassword(); 
            mt_srand((double)microtime()*1000000);
            $salt = pack("CCCCCCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand());
            $hashedpass = "{SSHA}" . base64_encode( sha1( $pass . $salt, true) . $salt );

        //assemble object
            //$newuser['dn'] = "uid=$uid,$dn";
            $newuser['objectclass'][0] = "inetOrgPerson";
            $newuser['objectclass'][1] = "organizationalPerson";
            $newuser['objectclass'][2] = "person";
            $newuser['objectclass'][3] = "top";
            $newuser['objectclass'][4] = "posixAccount";
            $newuser['objectclass'][5] = "shadowAccount";
            $newuser['objectclass'][6] = "gsAccount";
            $newuser['cn'] = "$first $last";
            $newuser['sn'] = $last;
            $newuser['givenName'] = $first;
            $newuser['title'] = "Member";
            $newuser['uid'] = $uid;
            $newuser['uidnumber'] = (int) $uidno;
            $newuser['gidNumber'] = (int) 500;
            $newuser['homeDirectory'] = "/home/$uid";
            $newuser['loginShell'] = "/bin/bash";
            $newuser['gecos'] = "$first $last,,,";
            $newuser['shadowLastChange'] = (int) 10877;
            $newuser['shadowMax'] = (int) 99999;
            $newuser['shadowWarning'] = (int) 7;
            $newuser['mail'] = $email;
            $newuser['studentNumber'] = (int) $stuno;
            $newuser['hasPaid'] = "TRUE";
            $newuser['hasSignedTOS'] = "TRUE";
            $newuser['shadowExpire'] = (int) $expiry;
            $newuser['userpassword'] = $hashedpass;

        //add to directory
            ldap_add($con,"uid=$uid,$dn", $newuser);
            if (ldap_error($con) != "Success") {
                $error = ldap_error($con);
            }
        //adduser to members group
            $newmember['memberUid'] = $uid;
            ldap_mod_add($con, "cn=members,ou=groups,dc=geeksoc,dc=org", $newmember);
            if (ldap_error($con) != "Success") {
                $error = ldap_error($con);
            }

        //email user confirmation
            $userEmail = <<<EOT
Welcome to GeekSoc $first!

You may find your new account details below, but please change your password with the 'passwd' command immediately upon first login.

Username: $uid 
Password: $pass

You may login to the shell server via SSH at shell.geeksoc.org on port 22. IRC may be found at irc.geeksoc.org on port 6667 - #geeksoc is the official channel.

On Windows the program 'putty' may be used to login to the SSH server, while Mac/Linux users will already have SSH installed and may connect using the 'ssh' command from a terminal.

The recommended way of accessing IRC is setting up a persistent connection on Shell using screen and irssi, see http://quadpoint.org/articles/irssi for details on how to set this up.

Have fun, but please be responsible and abide with the terms of service.

GeekSoc
http://www.geeksoc.org/
EOT;
            // mail($email, "[GeekSoc] Your account has been created", $userEmail, "From: support@geeksoc.org");

        //email creation notice to gsag
            $adminEmail = <<<EOT
An account has been created by $user for $first $last:

Username: $uid
Email: $email
EOT;
            // mail("gsag@geeksoc.org", "[GeekSoc] New account created", $adminEmail, "From: support@geeksoc.org");

        //irc creation notice (#gsag)
            // $message = "#gsag Account created for $first $last: Username: $uid, Email: $email (by $user)";
            // $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            // socket_connect($sock, "irc.geeksoc.org", 5050);
            // socket_write($sock, $message, strlen($message));
            // socket_close($sock);


            $success = "Created user '$uid' with password '$pass'.";
        }
    }
?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

        <div class="span10">
          <div class="row-fluid">
            <div class="span4">
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
