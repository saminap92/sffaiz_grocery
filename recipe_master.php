<?php 

	$title = "Recipe Master";
	require ("header.php");
	include('./recipe.php');
	$addIng = 0;
	$ingEdit=0;
	$detResults = "";
	$ingredientId = "";
	$detId="";
	$price=0;
	$totalprice=0;
	
	$recipeId =$_SESSION['recipeId'];

	// fetch the menu to be copied

	if(isset($_POST['copyMenu'])){


		$recipeId    = $_POST['recipeId'];
		$toDate      = $_POST['toDate'];
		$newRecipeId = "";
		
		$query = "Insert into recipe_master (recipe_name, no_of_people, cook_id, date_cooked, food_type, is_public, comments)  select  concat('Copy- ',  recipe_name), no_of_people,  cook_id, '". $toDate . "', food_type, is_public, comments from recipe_master where recipe_id = " . $recipeId;

		//echo $query ;
		//exit();

		$response = mysqli_query($dbc, $query);
		if($response) {
			$newRecipeId = mysqli_insert_id($dbc);
			echo $newRecipeId;
			$_SESSION['recipeId'] = $newRecipeId;

			$_SESSION['msg'] = "Record saved";
			$query =  "Insert into recipe_detail ( recipe_id,  ingredient_id,  qty, uom, price, det_comment)  Select $newRecipeId,  ingredient_id,  qty, uom, price, det_comment from recipe_detail  where recipe_id = $recipeId" ;

			//echo $query ;
			//exit();

			$response = mysqli_query($dbc, $query);
			if($response) {
			
				$_SESSION['msg'] = "Record saved";
			}
			else {
				echo 'Could not add a record.';
				echo mysqli_error($dbc);
			}

			header("location: recipe_master.php?edit=$newRecipeId");
		}
		else {
			echo 'Could not add a record.';
			echo mysqli_error($dbc);
		}
	}
		


	// fetch the record to be updated
	if ( isset($_GET['edit']) ) {
		$recipeId = $_GET['edit'];
		
		$edit_state = true;   // To get the  update button

		$rec = mysqli_query($dbc, "SELECT recipe_name, no_of_people, recipe_id, cook_id, date_cooked, food_type, is_public, comments  from recipe_master where recipe_id =$recipeId");
		$record = mysqli_fetch_array($rec);
		$recipeName = $record['recipe_name'];
		$noOfPeople = $record['no_of_people'];
		$cookId     = $record['cook_id'];
		$dateCooked = $record['date_cooked'];
		$foodType   = $record['food_type'];
		$isPublic   = $record['is_public'];
		$comments   = $record['comments'];
		$_SESSION['recipeId']  = $recipeId;


		// Retrieve detail records
		$detResults = mysqli_query($dbc, "Select det_id, a.ingredient_id, ingredient_name, qty, uom, a.price, det_comment from recipe_detail a, ingredient_master b where a.ingredient_id = b.ingredient_id and  a.recipe_id = '$recipeId' order by ingredient_name ") or die ('Could not get Recipe details : ' . mysqli_connect_error());

		if(!empty($detResults)){
			$sumrec = mysqli_query($dbc, "SELECT sum( qty * a.price) totalprice from recipe_detail a, ingredient_master b  where  a.ingredient_id = b.ingredient_id and recipe_id = $recipeId ")  or die ('Could not get Ingredient details : ' . mysqli_connect_error()) ;

			$sumRecord = mysqli_fetch_array($sumrec);
			$totalprice = $sumRecord['totalprice'];
		}

	}

	

	if (isset($_GET['ingEdit'])) {
 
		$ingredientId = $_GET['ingEdit'];
		
		$rec = mysqli_query($dbc, "SELECT b.ingredient_name, a.qty , a.uom uom, price, det_id, b.default_uom, b.default_price default_price from recipe_detail a, ingredient_master b  where  a.ingredient_id = b.ingredient_id and recipe_id = $recipeId and a.ingredient_id = $ingredientId ")  or die ('Could not get Ingredient details : ' . mysqli_connect_error()) ;

		if(!empty($rec)) {
			$record = mysqli_fetch_array($rec);
			$ingName = $record['ingredient_name'];
			$qty = $record['qty'];
			$detId = $record['det_id'];
			$det_edit_state = true;

			if(!empty($record['uom'])){
				$uom = $record['uom'];
			}
			else {
				$uom = $record['default_uom'];
			}
			if($record['price'] == 0 ){
				$price = $record['default_price'];
			}
			else {
				$price = $record['price'];
			}

		}
		
	}

?>
<div>
	<form  method="POST" action="./recipe.php">
		<div class="input-group">

			<input type="hidden" name="recipe_id" value="<?php echo $recipeId; ?>">  
	
			<div class="col1">
				<label for recipeName > Recipe Name :</label>
				<input type="text" name="recipeName" value="<?php echo $recipeName; ?>"> 
			</div>
			<div class="col2">
				<label for cookId> Cooks Name :</label>
				<select   name="cookId" id="cookId" value="<?php echo $cookId; ?>">
					<option value = "0"     <?php echo ($cookId == '' )?"   selected":"" ?> >                        </option>
					<option value = "2"     <?php echo ($cookId == '2')?"   selected":"" ?> >Alefiyaben Ferozpurwala </option>
					<option value = "3"     <?php echo ($cookId == '3')?"   selected":"" ?> >Sk Alibhai Ferozpurwala </option>
					<option value = "24"     <?php echo ($cookId == '24')?" selected":"" ?> >Alifiyaben Kagalwala    </option>
					<option value = "6"     <?php echo ($cookId == '6')?"   selected":"" ?> >Fakheraben Shahdawala   </option>
					<option value = "23"    <?php echo ($cookId == '23')?"  selected":"" ?> >Fatemaben Pedhiwala     </option>
					<option value = "27"     <?php echo ($cookId == '27')?" selected":"" ?> >Fatemaben Sehorwala     </option>
					<option value = "4"     <?php echo ($cookId == '4')?"   selected":"" ?> >Fatemaben Shahdawala    </option>
					<option value = "22"    <?php echo ($cookId == '22')?"  selected":"" ?> >Fatemaben Yamani        </option>
					<option value = "8"     <?php echo ($cookId == '8')?"   selected":"" ?> >Insiyaben Bohri         </option>
					<option value = "30"     <?php echo ($cookId == '30')?" selected":"" ?> >Mohammedbhai Khambaty   </option>
					<option value = "9"      <?php echo ($cookId == '9')?"  selected":"" ?> >Muniraben Ranijiwala    </option>
					<option value = "25"     <?php echo ($cookId == '25')?" selected":"" ?> >Murtazabhai Partapurwala </option>
					<option value = "29"     <?php echo ($cookId == '29')?" selected":"" ?> >Mustansirbhai Mamawala   </option>
					<option value = "5"     <?php echo ($cookId == '5')?"   selected":"" ?> >Rashidaben Badri         </option>
					<option value = "28"     <?php echo ($cookId == '28')?" selected":"" ?> >Sk Shabbirbhai Shahdawala </option>
					<option value = "31"     <?php echo ($cookId == '31')?" selected":"" ?> >Yusufbhai Asgharali      </option>
					<option value = "32"     <?php echo ($cookId == '32')?" selected":"" ?> >Other cook                </option>
				</select>	
			</div>
		<div class="col1">
			<label for dateCooked>Date Cooking :</label>
			<input  type="date" name="dateCooked" value="<?php echo $dateCooked; ?>"> 
		</div> 
		<div class="col2">
			<label for > No Of People :</label>
			<input  type="number" name="noOfPeople" value="<?php echo $noOfPeople; ?>">
		</div>

		
		<div>
			<?php if ($edit_state == false): ?>
				<button type="submit" name="save" class="btn">Save</button>
			<?php else: ?>
				<button type="submit" name="update" class="btn">Update</button>
			<?php endif ;

			// Setup variables for the copy menu
			$_SESSION['fromDate'] = $dateCooked;
			$_SESSION['copymenu'] = $recipeId;
			?>
			<a class="btn" href="recipe_copy.php">Copy Menu</a>
		</div>

	</form>
</div> 


	<div class="buttonline">
			<ul>
				<li> <a  href="ing_add.php?addIng=<?php echo $recipeId; ?>">Quick Add </a> </li>
				<li> <a  href="ing_add.php?addIng=<?php echo $recipeId; ?>">Add Defaults </a></li>
				<!--
				<li> <a  href="ing_add.php?addIng=<?php echo $recipeId; ?>">Show Price </a></li>
				<li> <a  href="ing_add.php?addIng=<?php echo $recipeId; ?>">Show Details </a></li>
				<li> <a  href="ing_add.php?addIng=<?php echo $recipeId; ?>">Hide Editbox </a></li>
			-->				
			</ul>
	</div> 
	<br>

	<div >
	<?php if (!empty($recipeId))  { ?>
		<!-- This is the form to put in edit for ingredients -->
		<form  class="editform" method="POST" action="./recipe_ing.php">

			<input type="hidden" name="det_id" value="<?php echo $detId; ?>">  
			<input type="hidden" name="recipe_id" value="<?php echo $recipeId; ?>">  
			<input type="hidden" name="ingredient_id" value="<?php echo $ingredientId; ?>">  
			 <!--input type="hidden" name="price" value="<?php echo $price; ?>"> -->

			<?php if ($qty == 0) $qty = ""; ?>

			<div >
				<label for ingName  >Ingredient:</label>
				<input type="text" style = "width:15%" name="ingName" value="<?php echo $ingName; ?>">
				<label for qty  >Qty:</label>
				<input  style = "width:6%"  type="number" name="qty"  step=".01" autofocus value="<?php echo $qty; ?>">
				<label for uom  >UOM:</label>
				<input style = "width:10%"  type="text" name="uom" value="<?php echo $uom; ?>">
				<label for price  >Price:</label>
				<input  style = "width:7%"  type="number" name="price"  step=".01"  value="<?php echo $price; ?>">
				

				<?php if ($det_edit_state == false): ?>
					<button type="submit" name="save" class="btn">Save</button>
				<?php else: ?>
						<button type="submit" name="update" class="btn">Update</button>
				<?php endif ?>
			</div>
				<!-- <button type="submit" name="details" class="btn">Details</button>-->
		</form>
	</div> 


	

		<!-- This displays the ingredients List as read only  -->
		<table>
			<caption style="text-align: right;"> Total Price : <?php echo sprintf("%01.2f", $totalprice) ?></caption>
				<thead>
					<tr>
						<th>Ingredient</th>
						<th>Qty</th>
						<th>UOM</th>
						<th>Price</th>
						<th>Total</th>
						<th >Action</th>
						<th>  </th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($detResults)) { 
						$totalprice = 0;
						$rowprice=0;       ?>
						<?php while ($row = mysqli_fetch_array($detResults)){    
							
							$rowprice= $row['qty'] * $row['price']; 
							$totalprice = $totalprice +  $rowprice; ?>
							<tr>
								<td><?php echo $row['ingredient_name']; ?> </td>
								<td><?php echo $row['qty']; ?></td>
								<td><?php echo $row['uom']; ?></td>
								<td><?php echo $row['price']; ?></td>
								<td><?php echo sprintf("%01.2f", $rowprice) ?></td>
								<td>
									<a class="edit_btn" href="recipe_master.php?ingEdit=<?php echo $row['ingredient_id']; ?> &edit=<?php echo $recipeId; ?>">Edit</a>
								</td>
								<td>
									<a class="del_btn" href="recipe_ing.php?del=<?php echo $row['det_id']; ?>">Delete</a>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
		</table>


		<!-- This is where the messages will show -->
		<?php if (isset($_SESSION['msg'])): ?>
			<div class="msg">
				<?php
				echo $_SESSION['msg'];  
				unset($_SESSION['msg']);
				?>					
			</div>
		<?php endif ?>
</div>


<!-----------------------------
	<div class="input-group">
		<label for="reference">Food Type :</label>
			<select   name="foodType" id="foodType" value="<?php echo $foodType; ?>">
				<option value = "none"   <?php echo ($foodType == ''      )?"selected":"" ?> >       </option>
				<option value = "Veg"    <?php echo ($foodType == 'Veg'   )?"selected":"" ?> >Veg    </option>
				<option value = "NonVeg" <?php echo ($foodType == 'NonVeg')?"selected":"" ?> >NonVeg </option>
				<option value = "Rice"   <?php echo ($foodType == 'Rice'  )?"selected":"" ?> >Rice   </option>
				<option value = "Sweets" <?php echo ($foodType == 'Sweets')?"selected":"" ?> >Sweets </option>
				<option value = "Other"  <?php echo ($foodType == 'Other' )?"selected":"" ?> >Other  </option>
			</select>	

	<div class="input-group">
			<label for="comments"  >Comments :</label>
			<input type="text" name="comments" value="<?php echo $comments; ?>"> 
	</div  >
	<div >
		<ul style="padding-left: 0">
					<li><input  type="checkbox" id="isPublic"  name="isPublic" class="chkbx" value="<?php echo $isPublic; ?>" <?php echo ($isPublic==1 ? 'checked' : '');?> >Public ? </li>
		</ul>
	</div>
	</div>
	-->




<?php  } ?>
<?php 
	require ("footer.php");
?>
	