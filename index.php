 <?php
$title = "Welcome to FMB Fremont Site!";
require ("header.php");

?>

<!-- Main Menu options -->
<table celpadding="5" cellspacing="10" align="center">
	<tr>	
		<div style="text-align: right">
			<?php 
				if(isset($_SESSION['username'])) {
					echo  'Welcome '.  $_SESSION['username'] . '  ';
				}	
				else {
					echo  'Welcome Guest ';
				}
			?>
		</div>
	</tr>
</table>
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