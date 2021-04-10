<?php 

	$title = "Recipe List";
	require ("header.php");
	include('./recipe.php');
	
	$recipeId = "";
	$_SESSION['recipeId'] = "";

	$_SESSION['showSpices'] = "show";
	$_SESSION['hideVeg'] = 2;
	$_SESSION['showGrain'] = "";
	$_SESSION['showOil'] = "";
	$_SESSION['showFrozen'] = "";
	$_SESSION['showSauces'] = "";
	$_SESSION['showDairy'] = "";
	$_SESSION['showMeat'] = "";
	$_SESSION['showOthers'] = "";


?>

<?php if (isset($_SESSION['msg'])): ?>
	<div class="msg">
		<?php
		echo $_SESSION['msg'];  
		unset($_SESSION['msg']);
		?>					
	</div>
<?php endif ?>


<table>
	<thead>
		<tr>
			
			<th>Date Cooked</th>
			<th>Recipe Name</th>
			<th>Cooks Name</th>
			
			<th >Action</th>
			<th><a class="edit_btn" href="recipe_master.php ?>">New Recipe </a></th>
		</tr>
	</thead>
	<tbody>
		<?php while ($row = mysqli_fetch_array($results)){ ?>    
			<tr>
				
				<td><?php echo $row['date_cooked']; ?></td>
				<td><?php echo $row['recipe_name']; ?> </td>
				<td><?php echo $row['cook_name']; ?></td>
				
				
				<td>
					<a class="edit_btn" href="recipe_master.php?edit=<?php echo $row['recipe_id']; ?>">Edit</a>
				</td>
				<td>
					<a class="del_btn" href="recipe.php?del=<?php echo $row['recipe_id']; ?>">Delete</a>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
			
<?php 
	require ("footer.php");
?>