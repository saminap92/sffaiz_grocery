<?php
	$title = "Logout Page";
   	require ("header.php");
   	session_destroy();
   	echo "You have sucessfully logged out !! Click here to login <a href='login.php'>Login </a>" ;
?>
