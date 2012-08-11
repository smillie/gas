<?php
    require 'ldapconnect.php';
  
    if (ldap_bind($con, "uid=".$_POST['uid'].",".$dn, $_POST['password']) === false) {
    echo $_POST['uid'].",".$dn.": ".$_POST['password'];
    header( 'Location: index.php?error' );
    }
    else {
    $_SESSION['user'] = $_POST['uid'];
    $_SESSION['password'] = $_POST['password'];
    header( 'Location: details.php' );
    }

?>