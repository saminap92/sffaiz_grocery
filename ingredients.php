<?php 

	$title = "Data Entry for Ingredients";
	require ("header.php");
	require_once('./mysqli_connect.php');

    $recipeId = "";
	$recipeName     = '';
	$noOfPeople   = '';
	$cookName   ="";
	$dateCooked = "";
	$foodType = "";
	$isPublic = "";
	$comments = "";
	$edit_state = false;

	//details fields
	$ingId="";
	$ingName = "";
	$qty = "";
	$uom="";
	$price="";
	$detId = "";
	$det_edit_state = false;


	$recipeId = $_SESSION['recipeId'];

	if(!empty($_POST['check_list'])) {
		foreach($_POST['check_list'] as $check) {
			//echo $check;
			//echo $recipeId;
			
			$query = "Insert into recipe_detail (recipe_id, ingredient_id) values ('$recipeId', '$check')";

			$response = mysqli_query($dbc, $query);
			if($response) {
				$_SESSION['msg'] = "Record saved";
				header("location: recipe_master.php?edit=$recipeId");
			}
			else{
				echo 'Could not add a record.';
				echo mysqli_error($dbc);
			}
		}
	}
						
	
	//  Get all the entered details here	
	if(isset($_POST['save'])){

		$ingName    = $_POST['ingName'];
		$qty = $_POST['qty'];
		$uom = $_POST['uom'];
		$price = $_POST['price'];

		//  Prepare the query with LIKE  
		
		$stmt = $dbc->prepare("SELECT ingredient_id,  ingredient_name, default_uom, default_price  from ingredient_master where lower( ingredient_name)  = lower( ? ) " );
		$stmt->bind_param("s", $ingName);
		$stmt->execute();
		$stmt->bind_result($ingredientId, $ingName, $uom, $price );
		$stmt->fetch();

		if( empty($ingredientId)) {
			$_SESSION['msg'] = "No such item, please enter another item or use Quickadd";
			header("location: recipe_master.php?edit=$recipeId");
		}
		else {

			$stmt->close();


			$query = "Insert into recipe_detail (recipe_id, ingredient_id, qty, uom, price ) values ('$recipeId', '$ingredientId', '$qty', '$uom' ,		 '$price')" OR 
	       die('Could not connect to MySQL : ' . mysqli_connect_error());


			$response = mysqli_query($dbc, $query) ;
			if($response) {
				$_SESSION['msg'] = "Record saved";
				header("location: recipe_master.php?edit=$recipeId");
			}
			else{
				echo 'Could not add a record.';
				echo mysqli_error($dbc);
			}
		}
	}

	if (isset($_POST['update'])) {
		$detId    = $_POST['det_id'];
		$qty = $_POST['qty'];
		$uom = $_POST['uom'];
		$price=$_POST['price'];
		echo  "in update" . $ingredient_id ;


		$response = mysqli_query($dbc, "UPDATE recipe_detail SET qty = $qty, uom = '$uom', price= '$price' WHERE det_id = '$detId'");
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
		$detId = $_GET['del'];

		$response = mysqli_query($dbc, "DELETE FROM recipe_detail  WHERE recipe_id= '$recipeId' and det_id = '$detId' ");
		if($response) {
			$_SESSION['msg'] = "Ingredient deleted";
			header("location: recipe_master.php?edit=$recipeId");
		}
		else {
			echo 'Could not delete the record.';
			echo mysqli_error($dbc);
		}
	}

 ?>
 <?php 
require ("footer.php");
?>