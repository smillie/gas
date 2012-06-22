<?php
	session_start();
	$_SESSION['user'] = $_POST['uid'];
	header( 'Location: details.php' );
?>