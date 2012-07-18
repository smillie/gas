<?php require 'ldapconnect.php'; ?>
<?php
if (!isUserInGroup($con, $user, "gsag")) {
    header( 'Location: index.php' );
}

$pageTitle = ' - All Users';

if (isset($_GET['search'])) {
    $pattern = $_GET['search'];
    $searchPattern = "(&(objectclass=posixaccount)(|(uid=*$pattern*)(cn=*$pattern*)(mail=*$pattern*)(studentnumber=*$pattern*)))";
    $pageTitle = " - Search for '$pattern'";
} else {
    $searchPattern = "(objectclass=posixaccount)";
}    

$search = ldap_search($con, $dn, $searchPattern);
ldap_sort($con, $search, 'uid');
$results = ldap_get_entries($con, $search);

if (ldap_count_entries($con, $search) == 1){
    $ruser = $results[0]['uid'][0];
    header( 'Location: edit.php?user='.$ruser );
} else if (ldap_count_entries($con, $search) == 0) {
    $error = "No results for '$pattern'";
}

?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

    <div class="span7">
        <div class="row-fluid">
            <table class="table ">
                <thead>
                  <tr>
                    <th></th>
                    <th>Account Name</th>
                    <th>Full Name</th>
                    <th>Student Number</th>
                    <th>Email</th>
                    <th>Status</th>
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

                <?php foreach (array_slice($results, 1) as $user) : ?>
                    <tr>
                    <td><a href="edit.php?user=<?php echo $user['uid'][0]?>" class="btn btn-mini">Edit</a></td>
                        <td><?php echo $user['uid'][0]; ?></td>
                        <td><?php echo $user['cn'][0]; ?></td>
                        <td><?php echo $user['studentnumber'][0]; ?></td>
                        <td><a href="mailto:<?php echo $user['mail'][0]?>"><?php echo $user['mail'][0]; ?></a></td>
                        <td><?php echo getStatus($user['shadowexpire'][0], $user['haspaid'][0]); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
              </table>    
        </div>
    </div>
<?php require 'footer.php'; ?>
