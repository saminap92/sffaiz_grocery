<?php 

	$title = "Print Grocery List";
	require ("header.php");
	//include('./print_list.php');

	$fromDate = $_SESSION['fromDate'];
	$toDate = $_SESSION['toDate'];
	$printBulk = "";


?>

<form method="POST" action="./print_list.php">
	<div  class="printDates" >
				<label for="fromDate">From Date </label>
				<input type="date" name="fromDate" style="max-width: 150px" required value=<?php echo $fromDate; ?>>
	</div>
	<div  class="printDates">			
				<label for="toDate">To Date </label>
				<input type="date" name="toDate" style="max-width: 150px"  required value=<?php echo $toDate; ?>> 
	</div>
	<div  >
				<input type="checkbox" name="printBulk"   value="<?php echo $printBulk; ?>"  <?php echo ($printBulk==1 ? 'checked' : '');?> >
				<label  for="printBulk" style="display: inline-block;";> Include Bulk ?</label>
				
				<!-- <td>
					<label>Include Meat Items ?</label>
					<input type="checkbox" name="printNonVeg" value="<?php echo $isPublic; ?>">
				</td> -->
	</div>
	<div  class="input-group">
		<button type="submit" name="viewFile" class="btn">View List</button> 
		<button type="submit" name="downloadFile" class="btn">Create List File</button>
		<a href="./grocery_list.txt" download="./grocery_list.txt" class = "btn"  style = "text-decoration: none;  padding: 10px;"> Download List</a>
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