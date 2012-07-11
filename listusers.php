<?php require 'ldapconnect.php'; ?>
<?php
if (!isUserInGroup($con, $user, "gsag")) {
    header( 'Location: index.php' );
}

function getStatus($expiry, $paid) {
    $day = intval(time()/(60*60*24));
    $i = (int)$expiry;
    if (!isset($expiry)) $i=99999;
    $status = "Active";
    $stat_icon = "icon-ok";
    if ($paid == "FALSE") {
      $status = "Not Paid";
      $stat_icon = "icon-exclamation-sign";
    } 
    if ($i <= ($day+60)) {
      $status = "Expiring";
      $stat_icon = "icon-exclamation-sign";
    }
    if ($i <= $day) {
      $status = "Expired";
      $stat_icon = "icon-exclamation-sign";
    }
    if ($i == 1) {
      $status = "Administratively Disabled";
      $stat_icon = "icon-ban-circle";
    }
    return "<i class='$stat_icon'></i> $status";
}

$search = ldap_search($con, $dn, "(objectclass=posixaccount)");
ldap_sort($con, $search, 'uid');
$results = ldap_get_entries($con, $search);



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
</div>
<?php require 'footer.php'; ?>
