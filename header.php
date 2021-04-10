<!DOCTYPE html>
<html lang = "en" >
	<head>
		<title><?php echo $title; ?></title>
		<meta charset="utf-8">
		<link rel="stylesheet"  href="style.css?version=63" >
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Starting session here so it is included in all pages -->
		<!-- Need this to access the username in all pages -->
		<?php
			session_start();
		?>
	</head>
	<body>
		<div id="container" >
			<header>
				<a href="index.php"><img src="images/ingredientspng.png" alt="Faiz Ingredients" ></a>
			</header>
			<nav>
					<a href="recipe_list.php"> Recipes </a> &nbsp; &nbsp;
					<a href="ing_master.php"> Ingredients </a> &nbsp; &nbsp; 
					<a href="print_form.php"> Print List </a> &nbsp; &nbsp; 
					<a href="index.php"> Admin </a> &nbsp; &nbsp;
					<a href="login.php"> Login </a> &nbsp; &nbsp;
			</nav>
			<main>
				