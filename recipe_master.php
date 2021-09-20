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


	// fetch the record to be updated
	if ( isset($_GET['edit']) ) {
		$recipeId = $_GET['edit'];
		
		$edit_state = true;   // To get the  update button

		$rec = mysqli_query($dbc, "SELECT recipe_name, no_of_people, recipe_id, cook_name, date_cooked, food_type, is_public, comments  from recipe_master where recipe_id =$recipeId");
		$record = mysqli_fetch_array($rec);
		$recipeName = $record['recipe_name'];
		$noOfPeople = $record['no_of_people'];
		$cookName   = $record['cook_name'];
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
			<label for cookName> Cooks Name :</label>
			<input type="text" name="cookName" value="<?php echo $cookName; ?>"> 
		</div>
		<div class="col1">
			<label for dateCooked>Date Cooking :</label>
			<input  type="date" name="dateCooked" value="<?php echo $dateCooked; ?>"> 
		</div> 
		<div class="col2">
			<label for > No Of People :</label>
			<input  type="number" name="noOfPeople" value="<?php echo $noOfPeople; ?>">
		</div>

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

	
	<div class="input-group">
		<?php if ($edit_state == false): ?>
			<button type="submit" name="save" class="btn">Save</button>
		<?php else: ?>
				<button type="submit" name="update" class="btn">Update</button>
		<?php endif ?>
		<!--<button type="submit" name="mainmenu" class="btn"> Main Menu</button>
		<button type="details" name="details" class="btn">Details</button> -->
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




<?php  } ?>
<?php 
	require ("footer.php");
?>
	