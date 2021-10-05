	<?php
	$title = "Ingredients Entry Page";
   	require ("header.php");
	require_once('./mysqli_connect.php');
	
	$ingredientId = '' ;
	$username ='';
	$userId = '';
	$ingredientName = '';
	$storeId = "";
	$defaultUom = "";
	$defaultPrice="";
	$ingComment="";
	$isBulk="";
	$catId="";
	$addedBy="";
	$timeAdded="";
	$storeName="";
	$categoryName="";
	$addedBy="";
	$addedTime="";


	$password = '';
	$readonly = '';
	$edit_state = false;


	if(!(isset($_SESSION['username']))){
		$_SESSION['msg'] = "Please log in first" ;
		header('location: login.php');
	}
	$username = $_SESSION['username'];
	$userId	  = $_SESSION['userId'];


	if (isset($_GET['del'])) {
		$ingredientId = $_GET['del'];

		// Allow deleting only if no menus are using the item 


		$rec = mysqli_query($dbc, "SELECT count(*) count from recipe_detail  WHERE ingredient_id= '$ingredientId' ");
		$record = mysqli_fetch_array($rec);
		$count = $record['count'];
			
		if ($count > 0){
			$_SESSION['msg'] = "Item cannot be deleted as there are menus that are using this item. Please contact the systems administrator"; 
		}
		else 
		{
			//  Get deletion details to log
			$stmt = $dbc->prepare("SELECT  ingredient_id, ingredient_name, store_id,  default_uom, default_price, ing_comment, isBulk, cat_id , added_by, time_added from ingredient_master where  ingredient_id = ? " );

			$stmt->bind_param("s", $ingredientId);
			$stmt->execute();
			$stmt->bind_result($ingredientId, $ingredientName , $storeId ,  $defaultUom , $defaultPrice , $ingComment, $isBulk, $catId, $addedBy, $addedTime);
			
			if($stmt->fetch()) {

					$ingString = "Ingredient_id :" . $ingredientId . ",ingredient_name : " . $ingredientName . ",Store Id : " . $storeId . ",UOM :" .  $defaultUom . ", Price :" . $defaultPrice . " ,Comment : " . $ingComment . ", isBulk : " . $isBulk . ", Category : " . $catId . ", Added by : " . $addedBy . ", Time added : " . $addedTime ;

					log_user($username .  " - Ingredients deleted : " . $ingString . " \n");
			}
			$stmt->close();
			// end log details 

			$response = mysqli_query($dbc, "DELETE FROM ingredient_master  WHERE ingredient_id= '$ingredientId'");
		
			if($response) {
					header('location: ing_list.php');
			}
			else {
				echo 'Could not delete the record.';
				echo mysqli_error($dbc);
			}
		}
	}


	if(isset($_POST['search']) or (isset($_GET['edit'])) ) {

	
		if (isset($_GET['edit'])) {
					$ingredientName = $_GET['edit'];
				}
		else { 
				// Allow regular search to manage ingredients
				$ingredientName = $_POST['ingredientName'];
		}

		
	    //  Prepare the query with LIKE  
		
		$stmt = $dbc->prepare("SELECT  ingredient_id, ingredient_name, i.store_id, store_name,  default_uom, default_price, ing_comment, isBulk, i.cat_id, cat_name from ingredient_master i, stores s, categories c where i.store_id = s.store_id and c.cat_id= i.cat_id  and ingredient_name = ? " );

		$stmt->bind_param("s", $ingredientName);
		$stmt->execute();
		$stmt->bind_result($ingredientId, $ingredientName , $storeId , $storeName, $defaultUom , $defaultPrice , $ingComment, $isBulk, $catId,  $categoryName);

		if($stmt->fetch()) {
				$edit_state = true;   // To get the  update button
				$readonly = 'readonly';
				//log_user($username . " " . " Ingredients Master Login \n");
				
		}
		else {
			$_SESSION['msg'] = "No such ingredient, please enter the name for new a ingredient or search again";
		}
		$_SESSION['showprofile'] = false;	
	}

	//  Get all the entered details here	
	if(isset($_POST['save'])){

		$ingredientId    = $_POST['ingredientId'];
		$ingredientName  = $_POST['ingredientName'];
		$storeId         = $_POST['storeId'];
		$defaultUom    = $_POST['defaultUom'];
		$defaultPrice  = $_POST['defaultPrice'];
		$ingComment    = $_POST['ingComment'];
		$isBulk        = $_POST['isBulk'];
		$catId        = $_POST['catId'];
		
		

		$query = "Insert into ingredient_master ( ingredient_name, store_id,  default_uom, default_price, ing_comment, isBulk, cat_id, added_by, time_added) values (  '$ingredientName' , '$storeId' , '$defaultUom' , '$defaultPrice' , '$ingComment', '$isBulk', '$catId', '$userId', now() )";
		$response = mysqli_query($dbc, $query);

		if($response) {
			$_SESSION['msg'] = "Record saved";
			$edit_state = true;

			// Get the newly created id for the form 
			$idrec = mysqli_query($dbc, "Select ingredient_id from ingredient_master where ingredient_name = '$ingredientName' ");
			$record = mysqli_fetch_array($idrec);
			$id = $record['ingredient_id'];
			$readonly = 'readonly' ;
			//log_user($username . " " . $ingredient_name . " " ." New Ingredient \n");
			
		}
		else{
			echo 'Could not add a record.';
			echo mysqli_error($dbc);
		}
	}

	if (isset($_POST['update'])) {
		
		$ingredientId    = mysqli_real_escape_string($dbc,$_POST['ingredientId']);

		//  Get old  details to log
		$stmt = $dbc->prepare("SELECT  ingredient_name, store_id,  default_uom, default_price, ing_comment, isBulk, cat_id , added_by, time_added  from ingredient_master where  ingredient_id = ? " );

		$stmt->bind_param("s", $ingredientId);
		$stmt->execute();
		$stmt->bind_result( $ingredientName , $storeId ,  $defaultUom , $defaultPrice , $ingComment, $isBulk, $catId , $addedBy, $addedTime);
			
		if($stmt->fetch()) {

				$ingString = "Ingredient_id :" . $ingredientId . ",ingredient_name : " . $ingredientName . ",Store Id : " . $storeId . ",UOM :" .  $defaultUom . ", Price :" . $defaultPrice . " ,Comment : " . $ingComment . ", isBulk : " . $isBulk . ", Category : " . $catId . ", Added by : " . $addedBy . ", Time added : " . $addedTime ;

				log_user($username .  " - Ingredients upgraded. Prev values : " . $ingString . " \n");
		}
		$stmt->close();

		
		$ingredientName  = mysqli_real_escape_string($dbc,$_POST['ingredientName']);
		$storeId         = mysqli_real_escape_string($dbc,$_POST['storeId']);
		$defaultUom    = mysqli_real_escape_string($dbc,$_POST['defaultUom']);
		$defaultPrice  = mysqli_real_escape_string($dbc,$_POST['defaultPrice']);
		$ingComment    = mysqli_real_escape_string($dbc,$_POST['ingComment']);
		$isBulk        = mysqli_real_escape_string($dbc,$_POST['isBulk']);
		$catId         = mysqli_real_escape_string($dbc,$_POST['catId']);

	
		$sqlqry = "UPDATE ingredient_master SET ingredient_name='$ingredientName',store_id='$storeId', default_uom='$defaultUom', default_price='$defaultPrice', ing_comment='$ingComment', isBulk='$isBulk', cat_id = '$catId' WHERE ingredient_id = '$ingredientId' " ;

		$response = mysqli_query($dbc, $sqlqry );
		if($response) {
			$_SESSION['msg'] = "Record updated";
			$edit_state = true;
			$readonly = 'readonly' ;
			
		}
		else{
			echo 'Could not update the record.';
			echo mysqli_error($dbc);
		}
	}

	if (isset($_POST['delete'])) {

			$ingredientId = $_POST['ingredientId'];
			

			// Allow deleting only if no menus are using the item 
			$count = mysqli_query($dbc, "SELECT count(*) from recipe_detail  WHERE ingredient_id= '$ingredientId' ");

			if ($count > 0){
				echo 'There are menus that are using this item. Please contact the systems administrator'; 
			}

			else {
				$response = mysqli_query($dbc, "DELETE FROM ingredient_master  WHERE ingredient_id= '$ingredientId' ");

				if($response) {
					$_SESSION['msg'] = "Record deleted";
					log_user($username . " Ingredient deleted  \n");
					$ingredientId = '' ;
					$username ='';
					$ingredientName = '';
					$storeId = "";
					$defaultUom = "";
					$defaultPrice="";
					$ingComment="";
					$isBulk="";
					$catId="";
					$addedBy="";
					$timeAdded="";
					$storeName="";
					$categoryName="";
					$edit_state = false;
				}
				else {
					echo 'Could not delete the record.';
					echo mysqli_error($dbc);
				}
			}
	}
	if (isset($_POST['new'])) {
		
		$ingredientId = '' ;
		$username ='';
		$ingredientName = '';
		$storeId = "";
		$defaultUom = "";
		$defaultPrice="";
		$ingComment="";
		$isBulk="";
		$catId="";
		$addedBy="";
		$timeAdded="";
		$storeName="";
		$categoryName="";

		$edit_state = false;
		header('location: ing_master.php');
	}


	?>
	<body>

		<form method="POST" action="./ing_master.php">

			<input type="hidden" name="ingredientId"      value="<?php echo $ingredientId; ?>"      >  
			<input type="hidden" name="storeId" value="<?php echo $storeId; ?>" >  
			<input type="hidden" name="catId"   value="<?php echo $catId; ?>"   >  

			<div class="input-group">
				<label for ingredientName > Ingredient Name</label>
				<input type="text" name="ingredientName" required value="<?php echo $ingredientName; ?>" <?php echo $readonly; ?> >
			</div>
		
			<label for="storeId">Store :</label>
			<select   name="storeId" id="storeId" value="<?php echo $storeName; ?>">
				<option value = "1"  <?php echo ($storeId == '1' )?"selected":"" ?> > Smart And Final  </option>
				<option value = "2"  <?php echo ($storeId == '2' )?"selected":"" ?> > Indian Store Bulk  </option>
				<option value = "3"  <?php echo ($storeId == '3' )?"selected":"" ?> > Costco  </option>
				<option value = "4"  <?php echo ($storeId == '4' )?"selected":"" ?> > Resturant Depot  </option>
				<option value = "5"  <?php echo ($storeId == '5' )?"selected":"" ?> > Amazon  </option>
				<option value = "6"  <?php echo ($storeId == '6' )?"selected":"" ?> > Namaste Plaza  </option>
				<option value = "7"  <?php echo ($storeId == '7' )?"selected":"" ?> > Ranch Market  </option>
				<option value = "8"  <?php echo ($storeId == '8' )?"selected":"" ?> > Herat  </option>
				<option value = "9"  <?php echo ($storeId == '9' )?"selected":"" ?> > Personal  </option>
				<option value = "10"  <?php echo ($storeId == '10' )?"selected":"" ?> > Others  </option>
			</select>	


			<label for="defaultUom">Default UOM :</label>
			<select   name="defaultUom" id="defaultUom" value="<?php echo $defaultUom; ?>">
				<option value = " "  <?php echo ($defaultUom == ''  )?"selected":"" ?> >         </option>
				<option value = "pcs"  <?php echo ($defaultUom == 'pcs' )?"selected":"" ?> > pcs  </option>
				<option value = "lbs"  <?php echo ($defaultUom == 'lbs')?"selected":"" ?> >lbs</option>
				<option value = "gms"  <?php echo ($defaultUom == 'gms' )?"selected":"" ?> >gms</option>
				<option value = "kgs"  <?php echo ($defaultUom == 'kgs' )?"selected":"" ?> >kgs</option>
				<option value = "packets"  <?php echo ($defaultUom == 'packets' )?"selected":"" ?> >packets    </option>
				<option value = "cups"  <?php echo ($defaultUom == 'cups' )?"selected":"" ?> >cups</option>
				<option value = "bunches"  <?php echo ($defaultUom == 'bunches' )?"selected":"" ?> >bunches  </option>
				<option value = "box"  <?php echo ($defaultUom == 'box' )?"selected":"" ?> >box  </option>
				<option value = "cans"  <?php echo ($defaultUom == 'cans' )?"selected":"" ?> >cans  </option>
				<option value = "big can" <?php echo ($defaultUom == 'big can')?"selected":"" ?> >big can  </option>
				<option value = "bottles"  <?php echo ($defaultUom == 'bottles' )?"selected":"" ?> >bottles</option>
				<option value = "big bottle" <?php echo ($defaultUom == 'big bottle')?"selected":"" ?> >big bottle  </option>
			</select>

			

			<label for="catId">Food Type :</label>
			<select   name="catId" id="catId"  required value="<?php echo $categoryName; ?>">
						<option value = "0"  <?php echo ($catId == '0' )?"selected":"" ?> >Standard  </option>
						<option value = "1"  <?php echo ($catId == '1' )?"selected":"" ?> >Spices  </option>
						<option value = "2"  <?php echo ($catId == '2' )?"selected":"" ?> >Fresh Veg</option>
						<option value = "3"  <?php echo ($catId == '3' )?"selected":"" ?> >Grain/Flour </option>
						<option value = "4"  <?php echo ($catId == '4' )?"selected":"" ?> >Nuts     </option>
						<option value = "5"  <?php echo ($catId == '5' )?"selected":"" ?> >Oils     </option>
						<option value = "6"  <?php echo ($catId == '6' )?"selected":"" ?> >Frozens  </option>
						<option value = "7"  <?php echo ($catId == '7' )?"selected":"" ?> >Sauces   </option>
						<option value = "8"  <?php echo ($catId == '8' )?"selected":"" ?> >Dairy    </option>
						<option value = "9"  <?php echo ($catId == '9' )?"selected":"" ?> >Meat     </option>
						<option value = "99" <?php echo ($catId == '99')?"selected":"" ?> >Others   </option>

			</select>


			<?php if(strpbrk($_SESSION['userprofile'],"GAS")) { ?>

					<label for="isBulk">Bulk Item :</label>
					<select   name="isBulk" id="isBulk" value="<?php echo $isBulk; ?>">
						<option value = "0"  <?php echo ($isBulk == '0'  )?"selected":"" ?> >  No       </option>
						<option value = "1"  <?php echo ($isBulk == '1' )?"selected":"" ?> > Yes </option>
					</select>	
			

					<div class="input-group">
						<label for defaultPrice > Default Price</label>
						<input type="text" name="defaultPrice" value="<?php echo $defaultPrice; ?>" >
					</div>

			<?php }  ?>

			<div class="input-group">
				<label for ingComment > Comments</label>
				<input type="text" name="ingComment" value="<?php echo $ingComment; ?>" >
			</div>
			
			<div class="input-group">
				<?php if ($edit_state == false): ?>
					<button type="submit" name="save" class="btn">Save</button>
					<button type="submit" name="search" class="btn">Search</button>
					<button type="submit" name="new" class="btn">New</button> 
				<?php else: ?>
						<button type="submit" name="update" class="btn">Update</button>
						<button type="submit" name="delete" class="btn">Delete</button>
						<button type="submit" name="new" class="btn">New</button>
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
