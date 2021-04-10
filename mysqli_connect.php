<?php

DEFINE ('DB_USER', 'sam-guest');
DEFINE ('DB_PASSWORD', 'guest2020');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'sam_rsvp');

$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)  OR 
       die('Could not connect to MySQL : ' . mysqli_connect_error());
//echo "after connect";
?>
