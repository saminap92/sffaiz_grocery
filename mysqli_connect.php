<?php

DEFINE ('DB_USER', 'saminap');
DEFINE ('DB_PASSWORD', 'q2xgn@@Ci[-0Ox8i');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'rsvp');



$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)  OR 
       die('Could not connect to MySQL : ' . mysqli_connect_error());
?>
