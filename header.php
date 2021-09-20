<!DOCTYPE html>
<html lang = "en" >
	<head>
		<title><?php echo $title; ?></title>
		<meta charset="utf-8">
		<link rel="stylesheet"  href="style.css?version=64" >
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Starting session here so it is included in all pages -->
		<!-- Need this to access the username in all pages -->
		<?php
			session_start();

			/*  Setting all Session variables here in the begining  */
			$_SESSION['fromDate'] = "";
			$_SESSION['toDate'] = "";
			$_SESSION['groceryDays'] = "40";
			$_SESSION['adminDays'] = "90";
			$_SESSION['usertype'] = "currentuser";
			
			
			if(!(isset($_SESSION['userprofile'])))
				$_SESSION['userprofile'] = "C";

			function log_user($logtxt) {
				$currtime = date("D, M d, Y h:m:s a T ");
				$handle = fopen('registration_log.txt', 'a');
				$logdetails = $currtime . " " . $logtxt;
				fwrite($handle, $logdetails);
				fclose($handle);

			}
		?>

	</head>
	<body>
		<div id="container" >
			<header>
				<a href="index.php"><img src="images/ingredientspng.png" alt="Faiz Ingredients" ></a>
			</header>
			<nav>
					<a href="recipe_list.php"> Recipes </a> &nbsp; &nbsp;
					<a href="ing_list.php"> Ingredients </a> &nbsp; &nbsp; 
					<?php 

						if(strpbrk($_SESSION['userprofile'],"GA")) { ?>
							<a href="print_form.php"> Print List </a> &nbsp; &nbsp; 
						<?php }

						if(strpbrk($_SESSION['userprofile'],"A")) { ?>
							<a href="admin.php"> Admin </a> &nbsp; &nbsp;
						<?php }

						if((isset($_SESSION['username'])))  {
							 // show current user profile only
							echo '<a href="user.php?usertype=currentuser"> Profile </a> &nbsp; &nbsp; ';
							echo '<a href="logout.php"> Logout </a> &nbsp; &nbsp; '; }
						else 
							echo '<a href="login.php" > Login  </a> &nbsp; &nbsp;' ;
					?>
			</nav>
			<main>
				