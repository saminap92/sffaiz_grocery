	<?php
	$title = "User Page";
   	require ("header.php");
	require_once('./mysqli_connect.php');
	
	$id = '' ;
	$username ='';
	$firstname = '';
	$lastname = '';
	$address = '';
	$phone = '';
	$password = '';
	$readonly = '';
	$edit_state = false;



	

	function log_user($logtxt) {
		$currtime = date("D, M d, Y h:m:s a T ");
		$handle = fopen('registration_log.txt', 'a');
		$logdetails = $currtime . " " . $logtxt;
		fwrite($handle, $logdetails);
		fclose($handle);

	}

    // Youtube How to send Email - John Morris

    function email_user($firstname, $address){
    	$to = $address;
    	$subject = "Thank you for registering at 92Tech.com" ;
    	$message = "<h3> Hello $firstname, <h3> <br>  Thank  your for registering at 92Tech.com. For Support call us at 510-555-5555 24/7. We hope you enjot the learning !!";
    	$headers = "From: The Sender Name <sender@92technologies.com \r\n" ;
    	$headers .= "Reply-To : replyto@92technologies.com\r\n";
    	$headers .= "Content-type: text/html \r\n";

    	//Send email 
    	if(!(mail($to, $subject, $message, $headers))){
    		echo "Error Sending email";
    	}
    }


	if(isset($_POST['search']) or ($_SESSION['showprofile'] == true )){

		if(!(isset($_SESSION['username']))){
		$_SESSION['msg'] = "Please log in first" ;
		header('location: login.php');
	}

		// Check if user has logged in and show his details to change profile
		if($_SESSION['showprofile'] ) {
			$username = $_SESSION['username'];
		}
		else{
			// Allow regular search to manage users
			$username = $_POST['username'];
		}
		
		
	    //  Prepare the query with LIKE  
		
		$stmt = $dbc->prepare("SELECT id,  firstname, lastname, address, password, phone  from user_crud where username LIKE ?" );
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->bind_result($id, $firstname, $lastname, $address, $password, $phone );

		if($stmt->fetch()) {
				$edit_state = true;   // To get the  update button
				$readonly = 'readonly';
				log_user($username . " " . $firstname . " " . $lastname ." User Login \n");
				
		}
		else {
			$_SESSION['msg'] = "No such user, please enter the username for new user or search again";
		}
		$_SESSION['showprofile'] = false;
		
	}

	//  Get all the entered details here	
	if(isset($_POST['save'])){
		$username   = $_POST['username'];
		$firstname  = $_POST['firstname'];
		$lastname   = $_POST['lastname'];
		$password   = $_POST['password'];
		$address    = $_POST['address'];
		$phone      = $_POST['phone'];
		

		$query = "Insert into user_crud (username, firstname, lastname, password, address, phone ) values ('$username',  '$firstname', '$lastname', '$password', '$address', '$phone' )";
		$response = mysqli_query($dbc, $query);

		if($response) {
			$_SESSION['msg'] = "Record saved";
			$edit_state = true;

			// Get the newly created id for the form 
			$idrec = mysqli_query($dbc, "Select id from user_crud where username = '$username' ");
			$record = mysqli_fetch_array($idrec);
			$id = $record['id'];
			$readonly = 'readonly' ;
			log_user($username . " " . $firstname . " " . $lastname ." New User \n");
			email_user($firstname, $address);
		}
		else{
			echo 'Could not add a record.';
			echo mysqli_error($dbc);
		}
	}

	if (isset($_POST['update'])) {
		$username  = mysqli_real_escape_string($dbc, $_POST['username']);
		$firstname = mysqli_real_escape_string($dbc, $_POST['firstname']);
		$lastname  = mysqli_real_escape_string($dbc, $_POST['lastname']);
		$password  = mysqli_real_escape_string($dbc, $_POST['password']);
		$address   = mysqli_real_escape_string($dbc, $_POST['address']);
		$phone     = mysqli_real_escape_string($dbc, $_POST['phone']);
		$id        =mysqli_real_escape_string($dbc, $_POST['id']);


		$sqlqry = "UPDATE user_crud SET username='$username',firstname='$firstname', lastname='$lastname', address='$address', password='$password', phone='$phone' WHERE id = '$id' " ;

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
	}

	if (isset($_POST['delete'])) {
		
		$username  = mysqli_real_escape_string($dbc, $_POST['username']);
		
		$response = mysqli_query($dbc, "DELETE FROM user_crud  WHERE id= '$id' ");

		if($response) {
			$_SESSION['msg'] = "Record deleted";
			log_user($username . " User deleted \n");
			$id = '' ;
			$username ='';
			$firstname = '';
			$lastname = '';
			$address = '';
			$phone = '';
			$password = '';
			$readonly = '';
			$edit_state = false;
			
		}
		else {
			echo 'Could not delete the record.';
			echo mysqli_error($dbc);
		}
	}
	if (isset($_POST['new'])) {
		
		$id = '' ;
		$username ='';
		$firstname = '';
		$lastname = '';
		$address = '';
		$phone = '';
		$password = '';
		$readonly = '';
		$edit_state = false;
		header('location: index.php');
	}


	?>
	<body>

		<form method="POST" action="./user.php">
			<input type="hidden" name="id" value="<?php echo $id; ?>">  
			<div class="input-group">
				<label> User Name</label>
				<input type="text" name="username" value="<?php echo $username; ?>" <?php echo $readonly; ?> >
			</div>
			<div class="input-group">
				<label> First Name</label>
				<input type="text" name="firstname" value="<?php echo $firstname; ?>" <?php echo $readonly; ?> >
			</div>
			<div class="input-group">
				<label> Last Name</label>
				<input type="text" name="lastname" value="<?php echo $lastname; ?>" <?php echo $readonly; ?> >
			</div>
			<div class="input-group">
				<label>Password</label>
				<input type="text" name="password" value="<?php echo $password; ?>">
			</div>
			<div class="input-group">
				<label> Email </label>
				<input type="text" name="address" value="<?php echo $address; ?>">
			</div>
			<div class="input-group">
				<label> Phone</label>
				<input type="text" name="phone" value="<?php echo $phone; ?>">
			</div>
			<div class="input-group">
				<?php if ($edit_state == false): ?>
					<button type="submit" name="save" class="btn">Save</button>
					<button type="submit" name="search" class="btn">Search</button>
					<button type="submit" name="new" class="btn">Main Menu</button> 
				<?php else: ?>
						<button type="submit" name="update" class="btn">Update</button>
						<button type="submit" name="delete" class="btn">Delete</button>
						<button type="submit" name="new" class="btn">Main Menu</button>
				<?php endif ?>
				
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
