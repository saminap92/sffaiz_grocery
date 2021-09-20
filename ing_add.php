<?php 
	$title = "Ingredients List";
	require ("header.php");
	require_once('./mysqli_connect.php');

	// Get all ingredients here
	
	$selectcols  = "Select store_name, cat_name,  ingredient_id, ingredient_name, default_uom   from ingredient_master m, stores s, categories c ";
	$joinclause  = " where s.store_id =m.store_id and  c.cat_id = m.cat_id ";
	$orderClause = " ORDER BY c.cat_id,  ingredient_name, store_name ";

	$whereClause = " ";

	$fullQuery = $selectcols . $joinclause . $whereClause  . $orderClause;

	$ing_array = mysqli_query($dbc, $fullQuery);


	$storeName="IndianStore";  ///  WHY IS THIS SET WITH THIS VALUE ???
	$recipeId = "";

?>

<?php if (isset($_SESSION['msg'])): ?>
	<div class="msg">
		<?php
			echo $_SESSION['msg'];  
			unset($_SESSION['msg']);
		?>
	</div>
<?php endif ?>


<!------------------------------------------------------------------->
<!-------------------  Code for Quick Add --------------------------->
<!------------------------------------------------------------------->


<?php 
	if (isset($_GET['addIng'])) {
			$recipeId = $_GET['addIng'];
	} ?>

	<form action="recipe_ing.php" method="post">
		<div>
			<table>
				
				<input type="hidden" name="recipe_id" value="<?php echo $recipeId; ?>">  
				
				<thead>
						<tr>
							<th colspan="2"> <input  class = "edit_btn" type="submit" /></th>
							<th colspan="3" align = "right"><a class="edit_btn" href="recipe_master.php?edit=<?php echo $recipeId; ?>">Back to Recipes </a></th>
						</tr>
						<tr>
							<th>Select</th>
							<th>Type</th>
							<th>Ingredient Name</th>
							<th>UOM</th>
						</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_array($ing_array)){ ?>
							<tr>
								<td> <input type="checkbox"  name="check_list[]" value="<?php echo $row['ingredient_id']; ?> "> </td>
								<td><?php echo $row['cat_name']; ?> </td>
								<td><?php echo $row['ingredient_name']; ?> </td>
								<td><?php echo $row['default_uom']; ?> </td>
							</tr>
					<?php } ?>
				</tbody>			
			</table>
		</div>		
	</form>
<?php 
	require ("footer.php");
?>