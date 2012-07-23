<?php require 'ldapconnect.php'; ?>
<?php
    if (!isUserInGroup($con, $user, "gsag")) {
        header( 'Location: index.php' );
    }

    $pageTitle = ' - Groups';

    if (isset($_GET['group'])) {
        $editGroup = TRUE;
        $gname = $_GET['group'];
        $pageTitle = " - Group: $gname";
        

        if (isset($_POST['newuser'])) {
            $newmember = $_POST['newuser'];
            $entry['memberuid'] = $newmember;
            ldap_mod_add($con, "cn=$gname,ou=groups,dc=geeksoc,dc=org", $entry);
            if (ldap_error($con) != "Success") {
                $error = ldap_error($con);
            }
            $success = "Added $newmember to group $gname.";

        } elseif (isset($_GET['remove'])) {
            $member = $_GET['remove'];
            $entry['memberuid'] = $member;
            ldap_mod_del($con, "cn=$gname,ou=groups,dc=geeksoc,dc=org", $entry);
            if (ldap_error($con) != "Success") {
                $error = ldap_error($con);
            }
            $success = "Removed $member from group $gname.";
        }


        $group = NULL;
        foreach(getAllGroups($con) as $g) {
            if ($g['cn'][0] == $gname) $group = $g;
        }

    }

?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

    <div class="span5">
        <div class="row-fluid">
                <?php if (isset($error)) : ?>
                  <div class="alert alert-error">
                    <?php echo "$error"; ?>
                  </div>
                <?php elseif (isset($success)) : ?>
                  <div class="alert alert-success">
                    <?php echo "$success"; ?>
                  </div>
                <?php endif; ?>

                <legend><?php echo $gname; ?></legend>
                <table class="table">
                    <thead>
                        <th></th>
                        <th>Member</th>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($group['memberuid'], 1) as $member) : ?>
                            <tr>
                            <td><a href="editgroup.php?remove=<?php echo $member?>&group=<?php echo $gname ?>" class="btn btn-mini btn-danger">Remove</a>
</td>
                                <td><?php echo $member."\n"; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <form class="well form-inline" action="editgroup.php?group=<?php echo $gname; ?>" method="post">
                    <input type="text" class="input-xlarge" placeholder="Username" name="newuser">
                    <button type="submit" class="btn">Add Member</button>
                </form>

        </div>
    </div>
<?php require 'footer.php'; ?>

