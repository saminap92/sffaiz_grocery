<?php
	$title = "File Info";
	require ("header.php");

	// PHP to list all files and allow download 
	// Youtube Adnan Afzal
	if(isset($_POST["login"])){

		//  From https://doc.bccnsoft.com/docs/php-docs-7-en/function.headers-sent.html
		if (!headers_sent($filename, $linenum)) {
    		header('location: login.php');
    		exit;
		// Trigger an error here.
		} else {

    		echo "Headers already sent in $filename on line $linenum\n" .
         	"Cannot redirect, for now please click this <a " .
          	"href=\"http://localhost/mod5/login.php\">link</a> instead\n";
    		exit;
		}	
	}

	// For the following, user has to be logged in 
	if(isset($_SESSION['username'])){

		if(isset($_POST["download"])) {

			$files = scandir("uploads");
			for ($filenum  = 2; $filenum < count($files); $filenum++){
				?>
				<p>
					<a href="uploads/<?php echo $files[$filenum] ?>" download="<?php echo $files[$filenum] ?>" >   <?php echo $files[$filenum] ?>  </a>
				</p>
				
				<?php 
			}
		}

		if(isset($_POST["viewlogs"])){		
			$handle = fopen("registration_log.txt", "r");
			echo "<br><br>" ;
			if ($handle) {
				while (($line = fgets($handle)) !== false) {
					echo $line;
					echo "<br>" ;
				}
				fclose($handle);
			} else {
				echo "Error opening log file" ;
			} 
			echo "<br><br>" ;
		}
		if(isset($_POST["uploadmenu"])){
			header('location: upload.php');
		}
		if(isset($_POST["print"])){
			header('location: print_form.php');
		}
		
		if(isset($_POST["recipe"])){
			header('location: recipe_list.php');
		}
		if(isset($_POST["manage"])){
			header('location: user.php');
		}

		if(isset($_POST["ing_master"])){
			header('location: ing_master.php');
		}
		
		if(isset($_POST["profile"])){
		
			$_SESSION['showprofile'] = true;
			header('location: user.php');
		}
	}
	else {
		$_SESSION['msg'] = "Please login first";
		header('location:login.php');
	}
	
?> 
<body>
	<button onclick="window.location.href='index.php'">Return to Main Menu</button>
<?php 
	require ("footer.php");
?>
	