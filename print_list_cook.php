<?php 

$title = "Print Cookwise Grocery List";
require ("header.php");
//include('./print_details.php');
require_once('./mysqli_connect.php');

// Retrieve detail records
$selectlist  = "SELECT i.ingredient_name, m.cook_name, s.store_id,  d.qty, d.uom, i.isBulk, d.det_comment , m.comments ";
$selectFrom  = " FROM recipe_master m, recipe_detail d, ingredient_master i, stores s ";
$selectjoin  = " WHERE m.recipe_id = d.recipe_id and d.ingredient_id = i.ingredient_id  and s.store_id = i.store_id " ;

$selectsort  = " ORDER  BY  date_cooked, m.cook_name, i.cat_id, ingredient_id  ";

$selectWhere2 = " AND isBulk = 1 ";
$selectWhere3 = " AND isBulk = 0 ";
$bulkResults = "";
$detResults ="";


if(isset($_POST["mainmenu"])){
	header('location: index.php');
}


if(isset($_POST["downloadFile"])){		

	
	$fromDate = $_POST['fromDate'];
	$toDate = $_POST['toDate'];
	$cookname = "None";
	$printcookname = "None";
	$_SESSION['fromDate'] = $fromDate;
	$_SESSION['toDate'] = $toDate;

	$selectWhere = " AND m.date_cooked  between  STR_TO_DATE('$fromDate' , '%Y-%c-%d') and STR_TO_DATE('$toDate' , '%Y-%c-%d') ";
	$selectquery = $selectlist . $selectFrom . $selectjoin . $selectWhere . $selectWhere3 . $selectsort ;
	if(isset($_POST['printBulk'])) {
		$bulkquery  =  $selectlist . $selectFrom . $selectjoin . $selectWhere . $selectWhere2 . $selectsort ;
		$bulkResults = mysqli_query($dbc, $bulkquery ) or die ('Could not get list details : ' . mysqli_connect_error());
	} else { $bulkquery = "" ;}

    //echo $selectquery;
    //echo $bulkquery;
    //exit;

	$detResults = mysqli_query($dbc, $selectquery ) or die ('Could not get list details : ' . mysqli_connect_error());

	
	$handle = fopen("cook_list.txt", "w");
	echo "<br><br>" ;
	$printcookname = "None";

	if ($handle) {

		fprintf($handle, "\n\n%s \n" ,   "           *****************" );
		fprintf($handle, "%s \n" ,       "             Grocery List"    );
		fprintf($handle, "%s \n" ,       "           *****************" );

		fprintf($handle, "\n %s                 %s\n\n" , $fromDate, $toDate  );

		
		// Print Regular Results here
		if (!empty($detResults)){

			fprintf($handle, "\n\n\n%s \n" ,   "======================="   );
			fprintf($handle, "%s\n" ,          "Cook wise List "               );
			fprintf($handle, "%s \n" ,         "======================="   );
			$printcookname = "None";

			while ($row = mysqli_fetch_array($detResults)){
				if ($printcookname <>  $row['cook_name']) {
					$printcookname = $row['cook_name'];
					fprintf($handle, "\n%s \n" ,   "~~~~~~~~~~~~~~~~~~~~~~~~~~~"   );
					fprintf($handle, "%s %s \n" , "Store : " , $printcookname);
					//fprintf($handle, "%s \n" ,   "-------------------------------------------" );
					//fprintf($handle, "%s \n" ,   "     Ingredient name   Qty Uom     Comments" );
					//fprintf($handle, "%s \n" ,   "-------------------------------------------" );
				}
				fprintf($handle, "%20s %5s %s\n" ,   $row['ingredient_name'], $row['qty'], $row['uom'] );
			}
		}
		fprintf($handle, "\n%s \n" ,   "~~~~~~~~~~~~~~~~~~~ END ~~~~~~~~~~~~"   );
		fclose($handle);
		$_SESSION['msg'] = "Cook_list.txt created";
		header('location: print_form.php');		

	} else {
		echo "Error opening log file" ;
	} 
		echo "<br><br>" ;
}




$printcookname = "None";
if(isset($_POST["viewFile"])) {
		$fromDate = $_POST['fromDate'];
		$toDate = $_POST['toDate'];
		$cookname = "None";
		$printcookname = "None";
		$_SESSION['fromDate'] = $fromDate;
		$_SESSION['toDate'] = $toDate;
		$selectWhere = " AND m.date_cooked  between  STR_TO_DATE('$fromDate' , '%Y-%c-%d') and STR_TO_DATE('$toDate' , '%Y-%c-%d') ";
		$selectquery = $selectlist . $selectFrom . $selectjoin . $selectWhere . $selectWhere3 . $selectsort ;

		if(isset($_POST['printBulk'])) {
			$bulkquery  =  $selectlist . $selectFrom . $selectjoin . $selectWhere . $selectWhere2 . $selectsort ;
			$bulkResults = mysqli_query($dbc, $bulkquery ) or die ('Could not get list details : ' . mysqli_connect_error());
		} else { $bulkquery = "" ;}
		//echo $selectquery;
    	//echo $bulkquery;
    	//exit;

		$detResults = mysqli_query($dbc, $selectquery ) or die ('Could not get list details : ' . mysqli_connect_error());
	}
?>
	

<table style="width:75%">
	<thead>
		<tr>  
			<th colspan = 5 style = "text-align: center ;   font-size: 150% " > Grocery List  </th> 
		</tr>
		<tr>	
			<th colspan = 4 >
				<a class="edit_btn" href="print_form.php?>">Back</a>
			</th>
			<!--
			<th colspan = 1  style = "float : right;">
			<a class="edit_btn" href="recipe_master.php?ingEdit=<?php echo $row['ingredient_id']; ?> &edit=<?php echo $recipeId; ?>">Download</a>
			</th>
		-->
		</tr>				
		<tr style = "background-color: powderblue;">  
			<th colspan = 2 style = "text-align: left ; font-size: 90% "> From Date : <?php echo $fromDate; ?>  </th> 
			<th> </th>
			<th colspan = 2 style = "text-align: right ; font-size: 90% " > To Date : <?php echo $toDate; ?>  </th> 
		</tr>
		<tr> 
			<?php if (!empty($bulkResults)) { ?>
				<tr>  
					<th colspan = 5> Bulk Purchases - Check Inventory </th> 
				</tr>
				<tr style = "background-color:  #cccccc;">
					<th>Store</th>
					<th>Ingredient</th>
					<th>Qty</th>
					<th>UOM</th>
					<th >Details</th>
				</tr>
			<?php } ?>
		</tr>	
	</thead>
	<tbody>
		<?php if (!empty($bulkResults)) { ?>
			<?php while ($row = mysqli_fetch_array($bulkResults))
				{  	
					if ($cookname <>  $row['cook_name']) {
						$cookname = $row['cook_name'];
						$printcookname = $row['cook_name'];
					}
					elseif  ($printcookname == $cookname) {
							$printcookname = "";
					}
				?>
					<tr>
						<td><?php echo $printcookname; ?> </td>
						<td><?php echo $row['ingredient_name']; ?> </td>
						<td><?php echo $row['qty']; ?></td>
						<td><?php echo $row['uom']; ?></td>
						<td><?php echo $row['det_comment'] . $row['comments']; ?></td>
					</tr>
			<?php } ?>
		<?php } ?>
	</tbody>
</table>
<?php 
	$cookname = "None";
	$printcookname = "None";
?>

<table style="width:75%">
	<thead>
			<tr>  
				<th colspan = 5> Store Wise Breakup  </th> 
			</tr>
			<tr style = "background-color:  #cccccc ;">
				<th>Store</th>
				<th>Ingredient</th>
				<th>Qty</th>
				<th>UOM</th>
				<th >Details</th>
			</tr>
	</thead>
	<tbody>
		<?php if (!empty($detResults)) { ?>
			<?php while ($row = mysqli_fetch_array($detResults))
			{  	
			if ($cookname <>  $row['cook_name']) {
							$cookname = $row['cook_name'];
							$printcookname = $row['cook_name'];
				}
				elseif  ($printcookname == $cookname) {
							$printcookname = "";
				}
			?>
			<tr>
				<td><?php echo $printcookname; ?> </td>
				<td><?php echo $row['ingredient_name']; ?> </td>
				<td><?php echo $row['qty']; ?></td>
				<td><?php echo $row['uom']; ?></td>
				<td><?php echo $row['det_comment'] . $row['comments']; ?></td>
			</tr>
			<?php } ?>
		<?php } ?>
	</tbody>
</table>
							

<?php if (isset($_SESSION['msg'])): ?>
	<div class="msg">
		<?php
		echo $_SESSION['msg'];  
		//unset($_SESSION['msg']);
		?>
	</div>
<?php endif ?>
<?php 
	require ("footer.php");
?>