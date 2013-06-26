<?php 
	$PAGETITLE= "Create a new character";
	$PAGENAME= "Create Character";
	include "../header.php";
	include "../func.inc.php";
	include "../menu.php";

if ((isset($_POST['name'])) and (isset($_POST['sheet'])))
{
	/* Sanitize POST input */
	if (function_exists('sanitize'))
		$_POST = sanitize($_POST);
	else
	{
		print "<p>Server Error.</p>";
		die();
	}

	$name = $_POST['name'];
	$sheet = $_POST['sheet'];
	$query = "INSERT INTO `characters` (`uid`, `name`, `sheet`, `skill1`,`skill8`, `skill2`,`skill28`,`skill29`,`skill32`,`skill35`,`skill38`,`skill39`,`skill42`,`skill43`,`skill44`,`skill45`,`skill71`,`skill93`,`skill94`,`skill70`,`skill40`,`skill68`,`skill48`) VALUES (NULL, '$name', '$sheet', '2','2','1','2','1','1','1','1','1','2','1','1','1','2','1','1','1','1','1','1');";
	$result = mysql_query($query);
    $character_record = mysql_insert_id();
	echo $query;

	if (isset($_POST['user']) and ($_POST['user'] != ''))
	{
		$userid = $_POST['user'];
		$query = "INSERT INTO `users_characters` (`user` ,`character_record`) VALUES ('$userid', '$character_record');";
		$result = mysql_query($query);
		header("location:player.php?uid=" . $userid."");
	}
	else
		header("location:player_list.php");
}
?>

<form action="new_character.php" method="post">
	<input name="user" type="hidden" value="<?php echo $_GET['user']; ?>" />
	<p>Name: <input name="name" type="text" /><br />
		<textarea name='sheet' rows=30 cols=50></textarea><br />
	</p>
	<input type="submit" value="Create" />
</form>
	
<?php include "../footer.php"; ?>
