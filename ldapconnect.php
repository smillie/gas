<?php 
  session_start();
  session_regenerate_id();

  $server = "ldap://ldap.geeksoc.org";
  $dn = "ou=People,dc=geeksoc,dc=org";
  if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $password = $_SESSION['password'];
    $userdn = "uid=".$user.",".$dn;
  }

  $con = ldap_connect($server);
  ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);

  if(isset($_SESSION['user'])) {
    if (ldap_bind($con, $userdn, $password)===false){
      echo "nope";
    }
    $user_search = ldap_search($con, $dn, "(uid=$user)");
    $user_get = ldap_get_entries($con, $user_search); 

    $avatar = md5( strtolower( trim($user_get[0]["mail"][0] ) ) );
  }

  function isUserInGroup($con, $user, $group) {
      $group_search = ldap_search($con, "cn=$group,ou=groups,dc=geeksoc,dc=org", "(memberUid=$user)");
      if (ldap_count_entries($con, $group_search) >= 1) {
          return true;
      } else {
          return false;
      }
  }

      function generatePassword ($length = 8) {
        $password = "";
        $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
        $maxlength = strlen($possible);
        if ($length > $maxlength) {
          $length = $maxlength;
        }
        $i = 0; 
        while ($i < $length) { 
          $char = substr($possible, mt_rand(0, $maxlength-1), 1);
          if (!strstr($password, $char)) { 
            $password .= $char;
            $i++;
          }
        }
        return $password;
    }
?>
