<?php 

	$title = "Data Entry for Recipe";
	require_once ("header.php");
	require_once('./mysqli_connect.php');

	$recipeName     = '';
	$noOfPeople   = '';
	$cookName   ="";
	$cookId = "0";
	$dateCooked = "";
	$foodType = "";
	$isPublic = "";
	$comments = "";
	$edit_state = false;

	//details fields
	$ingId="";
	$ingName = "";
	$qty = "";
	$uom = "";
	$det_edit_state = false;


	if(!(isset($_SESSION['username']))){
		$_SESSION['msg'] = "Please log in first" ;
		header('location: login.php');
	}
	$username = $_SESSION['username'];
	$userId	  = $_SESSION['userId'];



	//  Get all the entered details here	
	if(isset($_POST['save'])){

		$recipeName     = $_POST['recipeName'];
		$noOfPeople  = $_POST['noOfPeople'];
		$cookName  = $_POST['cookName'];
		$dateCooked  = $_POST['dateCooked'];
		$foodType  = $_POST['foodType'];
		$comments  = $_POST['comments'];
		isset( $_POST['isPublic']) ? $isPublic = "1" : $isPublic = "0" ;


		$query = "Insert into recipe_master (recipe_name, no_of_people ,cook_name, date_cooked,food_type, is_public, comments, cook_id) values ('$recipeName', '$noOfPeople' , '$cookName', '$dateCooked' ,'$foodType', '$isPublic', '$comments', '$cookId' )";

		//echo $query ;

		$response = mysqli_query($dbc, $query);
		if($response) {
			$recipeId = mysqli_insert_id($dbc);
			echo $recipeId;
			$_SESSION['recipeId'] = $recipeId;

			$_SESSION['msg'] = "Record saved";
			header("location: recipe_master.php?edit=$recipeId");
		}
		else{
			echo 'Could not add a record.';
			echo mysqli_error($dbc);
		}
	}

	if (isset($_POST['update'])) {
		$recipeName    = mysqli_real_escape_string($dbc, $_POST['recipeName']);
		$noOfPeople = mysqli_real_escape_string($dbc, $_POST['noOfPeople']);
		$recipeId      = mysqli_real_escape_string($dbc, $_POST['recipe_id']);
		$cookName  = $_POST['cookName'];
		$dateCooked  = $_POST['dateCooked'];
		$foodType  = $_POST['foodType'];
		isset( $_POST['isPublic']) ? $isPublic = "1" : $isPublic = "0" ;
		$comments  = $_POST['comments'];

		$response = mysqli_query($dbc, "UPDATE recipe_master SET recipe_name = '$recipeName', no_of_people = '$noOfPeople',  cook_name = '$cookName', date_cooked = '$dateCooked', food_type = '$foodType', is_public = '$isPublic', comments = '$comments' WHERE recipe_id= '$recipeId' "); 

		if($response) {
			$_SESSION['msg'] = "Record updated";
			header("location: recipe_master.php?edit=$recipeId");
		}
		else{
			echo 'Could not update the record.';
			echo mysqli_error($dbc);
		}
	}

	if (isset($_GET['del'])) {
		$recipeId = $_GET['del'];

		//  Get old  details to log
		$stmt = $dbc->prepare("SELECT  recipe_name, cook_name, cook_id, date_cooked from recipe_master where  recipe_id = ? " );

		$stmt->bind_param("s", $recipeId);
		$stmt->execute();
		$stmt->bind_result( $recipeName ,$cookName, $cookId, $dateCooked);
			
		if($stmt->fetch()) {

				$ingString = "recipeId :" . $recipeId . ",recipeName : " . $recipeName .  ", CookName :" . $cookName . ", CookId : " . $cookId . ", DateCooked : " . $dateCooked;

				log_user($username .  " - Recipe Deleted. Prev values : " . $ingString . " \n");
		}
		$stmt->close();
		//Add Log for recipe details here



		$response = mysqli_query($dbc, "DELETE FROM recipe_master  WHERE recipe_id= '$recipeId'");
		if($response) {
			$response = mysqli_query($dbc, "DELETE FROM recipe_detail  WHERE recipe_id= '$recipeId'");
			if($response) {
				$_SESSION['msg'] = "Record deleted";
				header('location: recipe_list.php');
			}
		}
		else {
			echo 'Could not delete the record.';
			echo mysqli_error($dbc);
		}
	}
	
	//////// Retrieve records
	$userid = $_SESSION['userId'];
	$sel_stmt = "Select * from recipe_master m ";
	
	//
	///// Set the condition based on the user profile
	//

	if(strpbrk($_SESSION['userprofile'],"A")) {

		$daysStr = '-' . $_SESSION['adminDays'] . 'days ' ;
		$todate = date('Y-m-d', strtotime($daysStr));

		$sel_stmt = $sel_stmt . " where date_cooked >= '$todate'  ";
	}  
	else if(strpbrk($_SESSION['userprofile'],"G")) {

		$daysStr = '-' . $_SESSION['groceryDays'] . 'days ' ;
		$todate = date('Y-m-d', strtotime($daysStr));

		$sel_stmt = $sel_stmt . " where date_cooked >= '$todate'  ";
	}  
	else if(strpbrk($_SESSION['userprofile'],"C")) {
		$sel_stmt = $sel_stmt . " , user_master f where f.user_id = m.cook_id   and m.cook_id = '$userid' ";
	}  

	$orderby_stmt = " order by date_cooked desc ";
	$sql_stmt = $sel_stmt . $orderby_stmt;
	
	$results = mysqli_query($dbc, $sql_stmt );

 ?>