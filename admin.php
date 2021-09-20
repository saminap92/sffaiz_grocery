<?php
	$title = "Admin Page";
	require ("header.php");
	require_once('./mysqli_connect.php');
	
	if(!(isset($_SESSION['username']))){
		$_SESSION['msg'] = "Please log in first" ;
		header('location: login.php');
	}
?>


<body>
	<?php
			$_SESSION['showprofile']=false;   //  This causes a new user form to be opened and not the current user data to be shown
			
    		//echo $_SESSION['usertype'] = 'newuser';
			//echo "usertype in admin = " ;
			//echo $_SESSION['usertype'];

			//echo '<a href="user.php?usertype=newuser"> Manage Users </a> &nbsp; &nbsp; ';
			echo '<a href="user_list.php"> Manage Users </a> &nbsp; &nbsp; ';
	?>

	<?php if (isset($_SESSION['msg'])): ?>
		<div class="msg">
			<?php
				echo $_SESSION['msg'];  
				unset($_SESSION['msg']);
			?>
		</div>
	<?php endif ?>
	<?php require ("footer.php"); ?>