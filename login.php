<?php
	$title = "Login Page";
	require ("header.php");
	$_SESSION['showprofile']=false;
	
	if(isset($_COOKIE['username']) and isset($_COOKIE['password'])) {
		$username = $_COOKIE['username'];
		$password = $_COOKIE['password'];
	}
	else {
		$username = "";
		$password = "";
	}
	
?>
<body>
	<form class="loginform" method="POST" action="./validate_user.php"> 
		<div class="login">
		<label for="username">Username :</label>
		<input type="text" id="username" name="username" value ="<?php echo $username; ?>" required > 
		<label for="password">Password :</label>
		<input type="password" id="password" name="password" value = "<?php echo $password; ?>" required >
				
		<input type="submit" id="login" name="login" value="Login" class="btn" >
		</div>
		<div>
		<ul style="padding-left: 0">
			<li>   <input type="checkbox" id="remember" name="remember" value="remember" class="chkbx" <?php if(isset($_COOKIE["username"])){ ?> checked <?php } ?> >Remember Me </li> 
		</ul>
		</div>
					
		<div style="clear:both"> New User ? <a href="user.php"> Sign up </a></div> 
	</form>

	<?php if (isset($_SESSION['msg'])): ?>
		<div class="msg">
			<?php
			echo $_SESSION['msg'];  
			unset($_SESSION['msg']);
			?>
		</div>
	<?php endif ?>

<?php 
   require ("footer.php");
?>
