<?php 
	$PAGETITLE= "Create a new character";
	$PAGENAME= "Create Character";
	include "../func.inc.php";
	include "../session.php";

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
	$query = "INSERT INTO `characters` (`uid`,`name`,`sheet`) VALUES (NULL,'$name','$sheet');";
	$result = mysql_query($query);
	$character_record = mysql_insert_id();


	if (isset($_POST['user']) and (intval($_POST['user']) > 0))
	{
		$userid = $_POST['user'];
		$query = "INSERT INTO `users_characters` (`user` ,`character_record`) VALUES ('$userid', '$character_record');";
		$result = mysql_query($query);
		header("location:player.php?uid=" . $userid."");
	}
	else
		header("location:player_list.php");
}

  include "../header.php";
  include "../menu.php";
?>

<form action="new_character.php" method="post">
	<input name="user" type="hidden" value="<?php echo $_GET['user']; ?>" />
	<p>Name: <input name="name" type="text" /><br />
		<textarea name='sheet' rows=30 cols=50></textarea><br />
	</p>
	<input type="submit" value="Create" />
</form>
	
<?php include "../footer.php"; ?>
