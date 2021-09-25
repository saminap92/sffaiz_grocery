<?php 

	$title = "Copy Menu";
	require ("header.php");
	

	if(!(isset($_SESSION['username']))){
		$_SESSION['msg'] = "Please log in first" ;
		header('location: login.php');
	}
	$fromDate = $_SESSION['fromDate'];
	$toDate = "";
	$recipeId=$_SESSION['copymenu'];


		

?>
<form method="POST" action="./recipe_master.php">
	<input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
	<div  class="printDates" >
				<label for="fromDate">From Date </label>
				<input type="date" name="fromDate" style="max-width: 150px" required value=<?php echo $fromDate; ?>>
	</div>
	<div  class="printDates">			
				<label for="toDate">To Date </label>
				<input type="date" name="toDate" style="max-width: 150px"  required value=<?php echo $toDate; ?>> 
	</div>

	<div  class="input-group">
		<button type="submit" name="copyMenu" class="btn">Copy Menu</button>
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