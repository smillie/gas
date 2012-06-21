<?php 

$user = 'asmillie';

$server = "ldap://ldap.geeksoc.org";
$dn = "ou=People,dc=geeksoc,dc=org";

$con = ldap_connect($server);
ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);

$user_search = ldap_search($con, $dn, "(uid=$user)");
$user_get = ldap_get_entries($con, $user_search); 

$avatar = md5( strtolower( trim($user_get[0]["mail"][0] ) ) );
// 
// foreach ($user_get[0] as $e) {
// 	echo $e."<br />";
// }

?>
<!-- <p>Username: <?php echo $user_get[0]["uid"][0]; ?></p>
<p>Name: <?php echo $user_get[0]["cn"][0]; ?></p>
<p>Title: <?php echo $user_get[0]["title"][0]; ?></p>
<p>Email: <?php echo $user_get[0]["mail"][0]; ?></p>
<p>Home Dir: <?php echo $user_get[0]["homeDirectory"][0]; ?></p> -->