<?php
	$title = "Logout Page";
   	require ("header.php");
   	session_destroy();
   	echo "You have sucessfully logged out !! " ;
   	header('location: index.php');
?>
