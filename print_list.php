<?php 

$title = "Print Grocery List";
require ("header.php");
//include('./print_details.php');
require_once('./mysqli_connect.php');

// Retrieve detail records
$selectlist  = "SELECT i.ingredient_name, s.store_name, s.store_id,  d.qty, d.uom, i.isBulk, d.det_comment , m.comments ";
$selectFrom  = " FROM recipe_master m, recipe_detail d, ingredient_master i, stores s ";
$selectjoin  = " WHERE m.recipe_id = d.recipe_id and d.ingredient_id = i.ingredient_id  and s.store_id = i.store_id " ;

$selectsort  = " ORDER  BY s.store_id, cat_id, d.ingredient_id, date_cooked  ";

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
	$storename = "None";
	$printstorename = "None";
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

	
	$handle = fopen("grocery_list.txt", "w");
	echo "<br><br>" ;
	$printstorename = "None";

	if ($handle) {

		fprintf($handle, "\n\n%s \n" ,   "           *****************" );
		fprintf($handle, "%s \n" ,       "             Grocery List"    );
		fprintf($handle, "%s \n" ,       "           *****************" );

		fprintf($handle, "\n %s                 %s\n\n" , $fromDate, $toDate  );

						// Print Bulk Results here
		if (!empty($bulkResults)){

			fprintf($handle, "%s \n" ,   "================================="   );
			fprintf($handle, "%s\n" ,    "Bulk Purchases - Check Inventory"    );
			fprintf($handle, "%s \n" ,   "================================="   );


			while ($row = mysqli_fetch_array($bulkResults)){
				if ($printstorename <>  $row['store_name']) {

					//if($printstorename <> "None") fprintf($handle, "\n\n %s", " " );  // print some extra spaces between stored l									
					$printstorename = $row['store_name'];
					fprintf($handle, "\n%s \n" ,   "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"   );
					fprintf($handle, "%s %s \n" , "Store : " , $printstorename);
					//fprintf($handle, "%s \n" ,   "-------------------------------------------" );
					//fprintf($handle, "%s \n" ,   "     Ingredient name   Qty Uom     Comments" );
					//fprintf($handle, "%s \n" ,   "-------------------------------------------" );

				}
				
				fprintf($handle, "%20s %5s %s %s \n" ,   $row['ingredient_name'], $row['qty'], $row['uom'], $row['det_comment'] );
				
			}
		}
		//  Bulk Results printing ends here

		// Print Regular Results here
		if (!empty($detResults)){

			fprintf($handle, "\n\n\n%s \n" ,   "======================="   );
			fprintf($handle, "%s\n" ,          "Store wise Purchases "               );
			fprintf($handle, "%s \n" ,         "======================="   );
			$printstorename = "None";

			while ($row = mysqli_fetch_array($detResults)){
				if ($printstorename <>  $row['store_name']) {
					$printstorename = $row['store_name'];
					fprintf($handle, "\n%s \n" ,   "~~~~~~~~~~~~~~~~~~~~~~~~~~~"   );
					fprintf($handle, "%s %s \n" , "Store : " , $printstorename);
					//fprintf($handle, "%s \n" ,   "-------------------------------------------" );
					//fprintf($handle, "%s \n" ,   "     Ingredient name   Qty Uom     Comments" );
					//fprintf($handle, "%s \n" ,   "-------------------------------------------" );
				}
				fprintf($handle, "%20s %5s %s %s \n" ,   $row['ingredient_name'], $row['qty'], $row['uom'], $row['det_comment'] );
			}
		}
		fprintf($handle, "\n%s \n" ,   "~~~~~~~~~~~~~~~~~~~ END ~~~~~~~~~~~~"   );
		fclose($handle);
		$_SESSION['msg'] = "Grocery_list.txt created";
		header('location: print_form.php');		

	} else {
		echo "Error opening log file" ;
	} 
		echo "<br><br>" ;
}




$printstorename = "None";
if(isset($_POST["viewFile"])) {
		$fromDate = $_POST['fromDate'];
		$toDate = $_POST['toDate'];
		$storename = "None";
		$printstorename = "None";
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
					if ($storename <>  $row['store_name']) {
						$storename = $row['store_name'];
						$printstorename = $row['store_name'];
					}
					elseif  ($printstorename == $storename) {
							$printstorename = "";
					}
				?>
					<tr>
						<td><?php echo $printstorename; ?> </td>
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
	$storename = "None";
	$printstorename = "None";
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
			if ($storename <>  $row['store_name']) {
							$storename = $row['store_name'];
							$printstorename = $row['store_name'];
				}
				elseif  ($printstorename == $storename) {
							$printstorename = "";
				}
			?>
			<tr>
				<td><?php echo $printstorename; ?> </td>
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