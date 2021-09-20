<?php 

	$title = "User List";
	require ("header.php");
	require_once('./mysqli_connect.php');
	
	$userId = "";
	$_SESSION['userId'] = "";

	

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
			
			<th>User Name</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Abbrev</th>
			<?php if(strpbrk($_SESSION['userprofile'],"S")) { ?>
				<th>Profile</th>
			<?php }   ?>
			
			<th >Action</th>
			<th><a class="edit_btn" href="user.php ?usertype=newuser">New User </a></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$sql_stmt = "Select  * from user_master m ";
			$results = mysqli_query($dbc, $sql_stmt );


		    while ($row = mysqli_fetch_array($results)){ ?>    
			<tr>
				
				<td><?php echo $row['username']; ?></td>
				<td><?php echo $row['firstname']; ?> </td>
				<td><?php echo $row['lastname']; ?></td>
				<td><?php echo $row['abbrev']; ?></td>
				<?php if(strpbrk($_SESSION['userprofile'],"S")) { ?>
					<td><?php echo $row['profile']; ?></td>
				<?php }   ?>
				
				
				<td>
					<a class="edit_btn" href="user.php?usertype=user&edit=<?php echo $row['username']; ?>">Edit</a>
				</td>
				<td>
					<a class="del_btn" href="user.php?usertype=user&del=<?php echo $row['user_id']; ?>">Delete</a>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
			
<?php 
	require ("footer.php");
?>