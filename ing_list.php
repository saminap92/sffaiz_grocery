<?php 

	$title = "Ingredient List";
	require ("header.php");
	require_once('./mysqli_connect.php');
	
	$ingId = "";
	$_SESSION['ingId'] = "";

	

	if(!(isset($_SESSION['username']))){
		$_SESSION['msg'] = "Please log in first" ;
		header('location: login.php');
	}

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
			
			<th>Type</th>
			<th>Ing  Name</th>
			<th>Store </th>
			<th>UOM</th>
			<th >Action</th>
			<th><a class="edit_btn" href="ing_master.php?ingId=newIng">New Ingredient </a></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$sql_stmt = "Select ingredient_id, cat_name, ingredient_name,store_name,default_uom from ingredient_master m , categories c, stores s  where m.cat_id = c.cat_id and m.store_id = s.store_id order by c.cat_id, ingredient_name";
			$results = mysqli_query($dbc, $sql_stmt );


		    while ($row = mysqli_fetch_array($results)){ ?>    
			<tr>
				
				<td><?php echo $row['cat_name']; ?></td>
				<td><?php echo $row['ingredient_name']; ?> </td>
				<td><?php echo $row['store_name']; ?></td>
				<td><?php echo $row['default_uom']; ?></td>
				
				
				<td>
					<a class="edit_btn" href="ing_master.php?edit=<?php echo $row['ingredient_name']; ?>">Edit</a>
				</td>
				<td>
					<a class="del_btn" href="ing_master.php?del=<?php echo $row['ingredient_id']; ?>">Delete</a>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
			
<?php 
	require ("footer.php");
?>