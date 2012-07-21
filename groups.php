<?php require 'ldapconnect.php'; ?>
<?php
if (!isUserInGroup($con, $user, "gsag")) {
    header( 'Location: index.php' );
}

$pageTitle = ' - Groups';

if (isset($_POST['newgroup']) && strlen($_POST['newgroup']) > 2) {
    $gname = $_POST['newgroup'];

    $gidno = 10000;
    foreach(getAllGroups($con) as $g) {
        if ($g['gidnumber'][0] > $gidno) 
            $gidno = $g['gidnumber'][0];
    }
    $gidno += 1;


    $newgroup['objectclass'] = "posixGroup";
    $newgroup['cn'] = $gname;
    $newgroup['userpassword'] = "{crypt}x";
    $newgroup['gidnumber'] = $gidno;

    ldap_add($con, "cn=$gname,ou=groups,dc=geeksoc,dc=org", $newgroup);
    if (ldap_error($con) != "Success") {
        $error = ldap_error($con);
    }
    $success = "Created group '$gname'.";

} elseif (isset($_GET['delete'])) {
    $gname = $_GET['delete'];
    ldap_delete($con, "cn=$gname,ou=groups,dc=geeksoc,dc=org");
    if (ldap_error($con) != "Success") {
        $error = ldap_error($con);
    }
    $success = "Deleted group '$gname'.";

}

?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

    <div class="span5">
        <div class="row-fluid">
            <table class="table ">
                <thead>
                  <tr>
                    <th></th>
                    <th>Group Name</th>
                    <th>GID</th>
                    <th>Members</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php if (isset($error)) : ?>
                  <div class="alert alert-error">
                    <?php echo "$error"; ?>
                  </div>
                <?php elseif (isset($success)) : ?>
                  <div class="alert alert-success">
                    <?php echo "$success"; ?>
                  </div>
                <?php endif; ?>

                <?php foreach (getAllGroups($con) as $group) : ?>
                    <tr>
                        <td><a href="groups.php?edit=<?php echo $group['cn'][0]?>" class="btn btn-mini">Edit</a></td>
                        <td><?php echo $group['cn'][0]; ?></td>
                        <td><?php echo $group['gidnumber'][0]; ?></td>
                        <td><?php echo count(array_slice($group['memberuid'], 1)); ?></td>

                        <div id="deleteModal<?php echo $group['cn'][0]; ?>" class="modal hide ">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h3>Are you sure?</h3>
                            </div>
                            <div class="modal-body">
                                <p>This will immediately delete the <?php echo $group['cn'][0]; ?> group from the LDAP directory.</p>
                                <p><strong>Note:</strong> Users in the group will not be deleted.</p>
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn" data-dismiss="modal" >Cancel</a>
                                <a href="groups.php?delete=<?php echo $group['cn'][0]?>" class="btn btn-danger">Confirm Delete</a>
                            </div>
                        </div>

                        <td><a data-toggle="modal" href="#deleteModal<?php echo $group['cn'][0]; ?>" class="btn btn-mini btn-danger">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
              </table>    
                <form class="well form-inline" action="groups.php" method="post">
                    <input type="text" class="input-xlarge" placeholder="Group Name" name="newgroup">
                    <button type="submit" class="btn">Add Group</button>
                </form>
        </div>
    </div>
<?php require 'footer.php'; ?>
