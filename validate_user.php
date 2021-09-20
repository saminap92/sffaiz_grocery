<?php
	$title = "User Page";
   	require ("header.php");
	require_once('./mysqli_connect.php');

	if(isset($_POST['login'])  ){

		if (headers_sent($filename, $linenum)) {
    		echo "Headers already sent in $filename on line $linenum\n" .
         	"Cannot redirect, for now please click this <a " .
          	"href=\"http://localhost/mod5/login.php\">link</a> instead\n";
    		exit;
		}

		$username = $_POST['username'];
		$password = $_POST['password'];

		


		echo "In Login";
		echo $username;
		echo $password;

		

		
	    //  Prepare the query with LIKE  
		
		$stmt = $dbc->prepare("SELECT user_id,  firstname, lastname, email, password, phone, abbrev, profile  from user_master where username = ?  and password = ? " )  OR 
       die('Could not connect to THIA  MySQL : ' . mysqli_connect_error());
       
		$stmt->bind_param("ss", $username, $password);
		$stmt->execute();
		$stmt->bind_result($userid, $firstname, $lastname, $email, $password, $phone, $abbrev, $profile );

		if($stmt->fetch()) {
				$edit_state = true;   // To get the  update button
				$readonly = 'readonly';
				$_SESSION['username']=$username;
				$_SESSION['userprofile'] = $profile;
				$_SESSION['userId'] = $userid;
				
				
				
				if(!empty($_POST["remember"])){
					setcookie("username", $_POST["username"], time() + (60*60*24*7));
					setcookie("password", $_POST["password"], time() + (60*60*24*7));
					
				}				
				else
				{
					if(isset($_COOKIE["username"])){
						setcookie("username", "");
					}
					if(isset($_COOKIE["password"])){
						setcookie("password", "");
					}
					
				} 
				header('location: index.php');
		}
	}

	?>
	<div> Invaid Username or password </div>
	<div> New User ? <a href="user.php"> Sign up </a></div>
	<div> Try again ?  <a href="login.php"> Sign in </a></div>
