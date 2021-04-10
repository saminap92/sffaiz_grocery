	<?php
	$title = "Ingredients Entry Page";
   	require ("header.php");
	require_once('./mysqli_connect.php');
	
	$id = '' ;
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



	$password = '';
	$readonly = '';
	$edit_state = false;


	if(isset($_POST['search'])){

		if(!(isset($_SESSION['username']))){
		$_SESSION['msg'] = "Please log in first" ;
		header('location: login.php');
		}
		
		$ingredientName  = $_POST['ingredientName'];

	    //  Prepare the query with LIKE  
		
		$stmt = $dbc->prepare("SELECT  ingredient_id, ingredient_name, i.store_id, store_name,  default_uom, default_price, ing_comment, isBulk, i.cat_id, cat_name from ingredient_master i, stores s, categories c where i.store_id = s.store_id and c.cat_id= i.cat_id  and ingredient_name = ? " );

		$stmt->bind_param("s", $ingredientName);
		$stmt->execute();
		$stmt->bind_result($id, $ingredientName , $storeId , $storeName, $defaultUom , $defaultPrice , $ingComment, $isBulk, $catId,  $categoryName);

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

		$id             = $_POST['id'];
		$ingredientName  = $_POST['ingredientName'];
		$storeId         = $_POST['storeId'];
		$defaultUom    = $_POST['defaultUom'];
		$defaultPrice  = $_POST['defaultPrice'];
		$ingComment    = $_POST['ingComment'];
		$isBulk        = $_POST['isBulk'];
		$catId        = $_POST['catId'];
		
		

		$query = "Insert into ingredient_master ( ingredient_name, store_id,  default_uom, default_price, ing_comment, isBulk, cat_id) values (  '$ingredientName' , '$storeId' , '$defaultUom' , '$defaultPrice' , '$ingComment', '$isBulk', '$catId' )";
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
		
		
		$id              = mysqli_real_escape_string($dbc,$_POST['id']);
		$ingredientName  = mysqli_real_escape_string($dbc,$_POST['ingredientName']);
		$storeId         = mysqli_real_escape_string($dbc,$_POST['storeId']);
		$defaultUom    = mysqli_real_escape_string($dbc,$_POST['defaultUom']);
		$defaultPrice  = mysqli_real_escape_string($dbc,$_POST['defaultPrice']);
		$ingComment    = mysqli_real_escape_string($dbc,$_POST['ingComment']);
		$isBulk        = mysqli_real_escape_string($dbc,$_POST['isBulk']);
		$catId         = mysqli_real_escape_string($dbc,$_POST['catId']);

	
		$sqlqry = "UPDATE ingredient_master SET ingredient_name='$ingredientName',store_id='$storeId', default_uom='$defaultUom', default_price='$defaultPrice', ing_comment='$ingComment', isBulk='$isBulk', cat_id = '$catId' WHERE ingredient_id = '$id' " ;

		$response = mysqli_query($dbc, $sqlqry );
		if($response) {
			$_SESSION['msg'] = "Record updated";
			$edit_state = true;
			$readonly = 'readonly' ;
			//log_user($username . " " . $ingredient_name . " " . " Ingredient updated \n");
		}
		else{
			echo 'Could not update the record.';
			echo mysqli_error($dbc);
		}
	}

	if (isset($_POST['delete'])) {

		$id             = $_POST['id'];
		
		
		$response = mysqli_query($dbc, "DELETE FROM ingredient_master  WHERE ingredient_id= '$id' ");

		if($response) {
			$_SESSION['msg'] = "Record deleted";
			//log_user($username . " User deleted \n");
			$id = '' ;
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
	if (isset($_POST['new'])) {
		
		$id = '' ;
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

			<input type="hidden" name="id"      value="<?php echo $id; ?>"      >  
			<input type="hidden" name="storeId" value="<?php echo $storeId; ?>" >  
			<input type="hidden" name="catId"   value="<?php echo $catId; ?>"   >  

			<div class="input-group">
				<label for ingredientName > Ingredient Name</label>
				<input type="text" name="ingredientName" value="<?php echo $ingredientName; ?>" <?php echo $readonly; ?> >
			</div>
		
			<label for="storeId">Store :</label>
			<select   name="storeId" id="storeId" value="<?php echo $storeName; ?>">
				<option value = "0"  <?php echo ($storeId == ''  )?"selected":"" ?> >         </option>
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


			
			<label for="catId">Food Type :</label>
			<select   name="catId" id="catId" value="<?php echo $categoryName; ?>">
				<option value = " "  <?php echo ($catId == ''  )?"selected":"" ?> >         </option>
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
				<option value = "10" <?php echo ($catId == '10')?"selected":"" ?> >Others   </option>

			</select>	


			<label for="isBulk">Bulk Item :</label>
			<select   name="isBulk" id="isBulk" value="<?php echo $isBulk; ?>">
				<option value = "0"  <?php echo ($isBulk == '0'  )?"selected":"" ?> >  No       </option>
				<option value = "1"  <?php echo ($isBulk == '1' )?"selected":"" ?> > Yes </option>
			</select>	

		
			<label for="defaultUom">Default UOM :</label>
			<select   name="defaultUom" id="defaultUom" value="<?php echo $defaultUom; ?>">
				<option value = "0"  <?php echo ($defaultUom == ''  )?"selected":"" ?> >         </option>
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
			<div class="input-group">
				<label for defaultPrice > Default Price</label>
				<input type="text" name="defaultPrice" value="<?php echo $defaultPrice; ?>" >
			</div>
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
