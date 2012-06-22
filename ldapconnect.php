<?php 
	session_start();

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
?>
