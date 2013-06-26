<?php 
	$PAGENAME = "Associate Character Sheets";
	$PAGETITLE= $PAGENAME;
	include "../header.php";
	include "../func.inc.php";
	include "../menu.php";
	
	if ($user['admin'] != 1)
	{
		header("location:logout.php");
		die();
	}

	if (function_exists('sanitize'))
	{
		$_POST = sanitize($_POST);
		$_GET = sanitize($_GET);
		
		$assc_user = $_GET['user'];
		$assc_char = $_GET['char'];

		if ((isset($_POST['usersel'])) and (isset($_POST['charsel'])))
		{
			$userid = intval($_POST['usersel']);
			$character_record = intval($_POST['charsel']);
			$query = "INSERT INTO `users_characters` (`user` ,`character_record`, `visible`) VALUES ('$userid', '$character_record', '0');";
			$result = mysql_query($query);
			header("location:character.php?uid=$character_record");
		}
	}
	else
	{
		print "<div class='error'><span class='title'>Server Error</span>Sanitization error.</div>";
	}



?>
	<p>This will associate a particular user with a particular character. The character sheet will be associated with that user (but by default will be invisible).</p>
	<p>A user can have many associated character sheets, and a single character can be associated with many users.</p>
	<form action="associate.php" method="post">
		<p><select name="usersel">
			<?php
				$query = "SELECT * FROM users";
				$result = mysql_query($query);
				while($row = mysql_fetch_array($result))
				{
					if ($assc_user == $row['uid'])
						echo "<option selected='selected' value='" . $row['uid'] . "'>".$row['username']."</option>";
					else
						echo "<option value='" . $row['uid'] . "'>".$row['username']."</option>";
				}
				
			?>
		</select> 

		<select name="charsel">
			<?php
				$query = "SELECT * FROM characters";
				$result = mysql_query($query);
				while($row = mysql_fetch_array($result))
				{
					if ($assc_char == $row['uid'])
						echo "<option selected='selected' value='" . $row['uid'] . "'>".$row['name']."</option>";
					else
						echo "<option value='" . $row['uid'] . "'>".$row['name']."</option>";
				}
				
			?>
		</select> 

		<input type="submit" value="Associate" /></p>

	</form>
</div>
<?php include "../footer.php"; ?>
