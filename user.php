<?php
	$title = "User Page";
	require ("header.php");
	require_once('./mysqli_connect.php');
	
	if(!(isset($_SESSION['username']))){
		$_SESSION['msg'] = "Please log in first" ;
		header('location: login.php');
	}

	// Check whether to show current user profile or users for admin
	if (isset($_GET['usertype'])) {
		$_SESSION['usertype'] = $_GET['usertype'];
	}
	
	$userid = '' ;
	$username ='';
	$firstname = '';
	$lastname = '';
	$email = '';
	$phone = '';
	$password = '';
	$abbrev = '';
	$profile = 'C';
	$readonly = '';
	$edit_state = false;

	

	function email_user($firstname, $email){
		$to = $email;
		$subject = "Thank you for registering at 92Tech.com" ;
		$message = "<h3> Hello $firstname, <h3> <br>  Thank  your for registering at Faiz Al Mawaid website. For Support call us at 510-555-5555 24/7!!";
		$headers = "From: The Sender Name <sender@92technologies.com \r\n" ;
		$headers .= "Reply-To : replyto@92technologies.com\r\n";
		$headers .= "Content-type: text/html \r\n";

    	//Send email 
		if(!(mail($to, $subject, $message, $headers))){
			echo "Error Sending email";
		}
	}

if (isset($_GET['del'])) {
		$userId = $_GET['del'];

		$response = mysqli_query($dbc, "DELETE FROM user_master  WHERE user_id= '$userId'");
		
		if($response) {
				$_SESSION['msg'] = "Record deleted";
				header('location: user_list.php');
		}
		else {
			echo 'Could not delete the record.';
			echo mysqli_error($dbc);
		}
	}

//echo "usertype  = " ;
//echo $_SESSION['usertype'];

if ($_SESSION['usertype']  == 'newuser') {

			$userid = '' ;
			$username ='';
			$firstname = '';
			$lastname = '';
			$email = '';
			$phone = '';
			$abbrev = '';
			$profile = '';
			$password = '';
			$readonly = '';
			$edit_state = false;
			
			
}




		if(isset($_POST['search']) or ($_SESSION['usertype']  == 'currentuser') or (isset($_GET['edit'])) ){

			//echo "in search";
			//echo $_SESSION['showprofile'] ;

		

			// Check if user has logged in and show his details to change profile
			if($_SESSION['usertype']  == 'currentuser') {
				$username = $_SESSION['username'];
			}
			else{
				if (isset($_GET['edit'])) {
					$username = $_GET['edit'];
				}
				else { 
				// Allow regular search to manage users
				$username = $_POST['username'];
				}
			}
			

		    //  Prepare the query with LIKE  
			
			$stmt = $dbc->prepare("SELECT user_id,  firstname, lastname, email, password, phone, profile, abbrev from user_master where username = ? " );
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($userid, $firstname, $lastname, $email, $password, $phone, $profile, $abbrev );

			if($stmt->fetch()) {
					$edit_state = true;   // To get the  update button
					$readonly = 'readonly';
					
					if($_SESSION['usertype']  == 'currentuser') {
						$_SESSION['userprofile'] = $profile;
						$_SESSION['userid'] = $userid;
					}
					log_user($username . " " . $firstname . " " . $lastname ." User Login \n");
				}
			else {
					$_SESSION['msg'] = "No such user, please enter the username for new user or search again";
			}
			$stmt->close();
			$dbc->next_result();
			$_SESSION['showprofile'] = false;
		}

	


		//  Get all the entered details here	
		if(isset($_POST['save'])){
				$username   = $_POST['username'];
				$firstname  = $_POST['firstname'];
				$lastname   = $_POST['lastname'];
				$password   = $_POST['password'];
				$email      = $_POST['email'];
				$phone      = $_POST['phone'];
				$abbrev     = $_POST['abbrev'];
				$profile    = "C";



				$query = "Insert into user_master (username, firstname, lastname, password, email, phone, abbrev, profile ) values ('$username',  '$firstname', '$lastname', '$password', '$email', '$phone' , '$abbrev', '$profile')";
				$response = mysqli_query($dbc, $query);

				if($response) {
					$_SESSION['msg'] = "Record saved";
					$edit_state = true;

				// Get the newly created id for the form 
					$idrec = mysqli_query($dbc, "Select user_id from user_master where username = '$username' ");
					$record = mysqli_fetch_array($idrec);
					$userid = $record['user_id'];
					$readonly = 'readonly' ;
					log_user($username . " " . $firstname . " " . $lastname ." New User \n");
					//email_user($firstname, $email);
				}
				else{
					echo 'Could not add a record.';
					echo mysqli_error($dbc);
				}
				$idrec->close();
			    $dbc->next_result();
		}


		if (isset($_POST['update'])) {
				$username  = mysqli_real_escape_string($dbc, $_POST['username']);
				$firstname = mysqli_real_escape_string($dbc, $_POST['firstname']);
				$lastname  = mysqli_real_escape_string($dbc, $_POST['lastname']);
				$password  = mysqli_real_escape_string($dbc, $_POST['password']);
				$email     = mysqli_real_escape_string($dbc, $_POST['email']);
				$phone     = mysqli_real_escape_string($dbc, $_POST['phone']);
				$userid    = mysqli_real_escape_string($dbc, $_POST['userid']);
				$abbrev    = mysqli_real_escape_string($dbc, $_POST['abbrev']);
				$profile   = mysqli_real_escape_string($dbc, $_POST['profile']);



				$sqlqry = "UPDATE user_master SET username='$username',firstname='$firstname', lastname='$lastname', email='$email', password='$password', phone='$phone' , abbrev='$abbrev',  profile='$profile' WHERE user_id = '$userid' " ;

				

				$response = mysqli_query($dbc, $sqlqry );
				if($response) {
					$_SESSION['msg'] = "Record updated";
					$edit_state = true;
					$readonly = 'readonly' ;
					log_user($username . " " . $firstname . " " . $lastname . " User updated \n");
				}
				else{
					echo 'Could not update the record.';
					echo mysqli_error($dbc);
				}

			    $dbc->next_result();

		}



		if (isset($_POST['delete'])) {

				$userid    = mysqli_real_escape_string($dbc, $_POST['userid']);
				$username  = mysqli_real_escape_string($dbc, $_POST['username']);
				
				$response = mysqli_query($dbc, "DELETE FROM user_master  WHERE user_id= '$userid' ");

				if($response) {
					$_SESSION['msg'] = "Record deleted";
					log_user($username . " User deleted \n");
					$userid = '' ;
					$username ='';
					$firstname = '';
					$lastname = '';
					$email = '';
					$phone = '';
					$abbrev = '';
					$profile = 'C';
					$password = '';
					$readonly = '';
					$edit_state = false;

				}
				else {
					echo 'Could not delete the record.';
					echo mysqli_error($dbc);
				}
		}
?>


	<body>
			<form method="POST" action="./user.php">
				<input type="hidden" name="userid" value="<?php echo $userid; ?>">  
				<div class="input-group">
					<label> User Name</label>
					<input type="text" name="username" value="<?php echo $username; ?>" <?php echo $readonly; ?> >
				</div>
				<div class="input-group">
					<label> First Name</label>
					<input type="text" name="firstname" value="<?php echo $firstname; ?>"  <?php echo $readonly; ?> >
				</div>
				<div class="input-group">
					<label> Last Name</label>
					<input type="text" name="lastname" value="<?php echo $lastname; ?>"  <?php echo $readonly; ?> >
				</div>



				<?php if(strpbrk($_SESSION['userprofile'],"SA") or ($_SESSION['usertype']  == 'currentuser') ) { ?>
				<div class="input-group">
					<label>Password</label>
					<input type="password" name="password" value="<?php echo $password; ?>">
				</div>
				<?php } ?>


				<div class="input-group">
					<label> Email </label>
					<input type="text" name="email" value="<?php echo $email; ?>">
				</div>
				<div class="input-group">
					<label> Phone</label>
					<input type="text" name="phone" value="<?php echo $phone; ?>">
				</div>
				<div class="input-group">
					<label> Abbreviation</label>
					<input type="text" name="abbrev" value="<?php echo $abbrev; ?>">
				</div>

				
				<?php if(strpbrk($_SESSION['userprofile'],"S")) { ?>
					<div class="input-group">
						<label>Profile</label>
						<input type="text" name="profile" value="<?php echo $profile; ?>">
					</div>
				<?php }  else  {  ?>
					<input type="hidden" name="profile" value="<?php echo $profile; ?>"> 
				<?php }   ?>

				
				<div class="input-group">

					<?php if ($edit_state == false) { ?>
						<button type="submit" name="save" class="btn">Save</button>
						
						<?php if(strpbrk($_SESSION['userprofile'],"A"))  { ?>
							<button type="submit" name="search" class="btn">Search</button>
						<?php  }
					}
					else
						{  ?>
							<button type="submit" name="update" class="btn">Update</button>
							<?php if(strpbrk($_SESSION['userprofile'],"A"))  { ?>
								<button type="submit" name="delete" class="btn">Delete</button>
								<?php
							}
						}
						$_SESSION['usertype']  == ''  // Resetting  usertype
						?>

					</div>
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
