<?php
$title = "Welcome to Recipe Site!";
require ("header.php");

/*  Setting all Session variables here in the begining  */

$_SESSION['fromDate'] = "";
$_SESSION['toDate'] = "";

?>


<!-- Main Menu options -->
<table celpadding="5" cellspacing="10" align="center">
	<tr>	
		<div style="text-align: right">
			<?php 
				if(isset($_SESSION['username'])) {
					echo  'Welcome '.  $_SESSION['username'] . '  ';
					echo "<a href='logout.php'>Logout</a>";
				}	
				else {
					echo  'Welcome Guest ';
				}
			?>
		</div>
	</tr>
	<tr> 			
		<form method="POST" action="./menu_functions.php"> 

			<div class="input-group">
				<button type="submit" name="recipe" class="btn">Manage Recipes </button>
				<button type="submit" name="viewlogs" class="btn">ViewLogs </button>
				<button type="submit" name="manage" class="btn">Manage Users </button>
				<button type="submit" name="ing_master" class="btn">Manage Ingredients </button>
				<button type="submit" name="profile" class="btn">Manage Profile </button>
				<button type="submit" name="print" class="btn">Print List </button>
				
				<?php if (!(isset($_SESSION['username']))): ?>
					<button type="submit" name="login" class="btn">Login </button>
				<?php endif ?>

			</div>
		</form>
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