<?php require 'ldapconnect.php'; ?>
<?php
    if (!isUserInGroup($con, $user, "gsag")) {
        header( 'Location: index.php' );
      }

      $mysqli = new mysqli($conf['db_host'], $conf['db_user'], $conf['db_pass'], $conf['db_name']);
      if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit();
      }

  header("Content-type: text/csv");
  header("Content-Disposition: attachment; filename=gs_members.csv");
  header("Pragma: no-cache");
  header("Expires: 0");

  /* Create the prepared statement */
    if ($stmt = $mysqli->prepare("SELECT ID, firstname, lastname, username, studentnumber, email, IS_DELETED FROM newusers WHERE IS_DELETED = false")) {

            /* Execute the prepared Statement */
            $stmt->execute();

            $stmt->bind_result($id, $first, $last, $uid, $stuno, $email, $created);
            $users[] = array("First Name","Last Name","Student Number","Email Address", "Has Paid?");
            while ($stmt->fetch()) {
                  // printf("%s %s %s %i %s\n", $first, $last, $uid, $stuno, $email);
                  $u['first'] = $first;
                  $u['last'] = $last;
                  $u['stuno'] = $stuno;
                  $u['email'] = $email;
                  $u['paid'] = "Not Paid";

                  $users[] = $u;
              }

            /* Close the statement */
            $stmt->close(); 
    }
    else {
            /* Error */
    }
    
    $searchPattern = "(objectclass=posixaccount)";
    $search = ldap_search($con, $dn, $searchPattern);
    ldap_sort($con, $search, 'uid');
    $results = ldap_get_entries($con, $search);

    if (ldap_count_entries($con, $search) == 1){
        $ruser = $results[0]['uid'][0];
        header( 'Location: edit.php?user='.$ruser );
    } else if (ldap_count_entries($con, $search) == 0) {
        $error = "No results for '$pattern'";
    }
    
    foreach (array_slice($results, 1) as $user) {
      $u['first'] = $user['uid'][0];
      $u['last'] = $user['cn'][0];
      $u['stuno'] = $user['studentnumber'][0];
      $u['email'] = $user['mail'][0];
      $haspaid = $user['haspaid'][0];
      $status = getStatus($user['shadowexpire'][0], $user['haspaid'][0]);
      $u['paid'] = "Not Paid";
      
      if($haspaid == "TRUE") {
        $u['paid'] = "Paid";
        $users[] = $u;
      } elseif(strpos($status, "Expir") && $haspaid == "FALSE") {
          $u['paid'] = "Not Paid";
          $users[] = $u;
        }
      
    }

  outputCSV($users);

  function outputCSV($data) {
      $outstream = fopen("php://output", "w");
      function __outputCSV(&$vals, $key, $filehandler) {
          fputcsv($filehandler, $vals); // add parameters if you want
      }
      array_walk($data, "__outputCSV", $outstream);
      fclose($outstream);
  }

?>