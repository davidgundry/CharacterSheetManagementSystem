<?php 
	$PAGENAME = "Administration Panel";
	$PAGETITLE = "Manage users and characters";
	include "../header.php";
	include "../func.inc.php";
	include "../menu.php";

	if ($user['admin'] != 1)
	{
		header("location:logout.php");
		die();
	}

	/* Sanitize GET input */
	if (function_exists('sanitize'))
		$_GET = sanitize($_GET);
	else
	{
		print "<div class='error'><span class='title'>Server Error</span>Sanitization error.</div>";
		die();
	}

	/* Handle Admin actions */
	if ($_GET['a'] == "deactivate")
	{
		$query = "UPDATE users SET active='0' WHERE uid='".$_GET['uid']."' ";
	  	$result = mysql_query($query);
	}

	if ($_GET['a'] == "activate")
	{
		$query = "UPDATE users SET active='1' WHERE uid='".$_GET['uid']."' ";
	  	$result = mysql_query($query);
	}

	if ($_GET['a'] == "delete")
	{
		$query = "DELETE FROM users WHERE uid='".$_GET['uid']."' ";
	  	$result = mysql_query($query);
		$query = "DELETE FROM users_characters WHERE user='".$_GET['uid']."' ";
	  	$result = mysql_query($query);
	}

	if ($_GET['a'] == "delchar")
	{
		$query = "DELETE FROM characters WHERE uid='".$_GET['charid']."' ";
		$result = mysql_query($query);
		$query = "DELETE FROM users_characters WHERE character_record='".$_GET['charid']."' ";
	  	$result = mysql_query($query);
	}

?>

<h2>Active Users</h2>
	<table>
		<?php
			$query = "SELECT * FROM users WHERE active='1' ";
			$result = mysql_query($query);
			echo "<tr class='table-heading'><th>Username</th><th>Email Address</th><th>Date of Registration</th></tr>";
			while($row = mysql_fetch_array($result))
			{
				echo "<tr><td><a href='player.php?uid=" .$row['uid'] . "' title='Show details of this player'>".$IMGUSER." " . $row['username'] . "</a></td><td>" . $row['email'] . "</td><td>" . $row['registration-date'] . "</td><td><a href='?a=deactivate&amp;uid=".$row['uid']."' title='This will prevent the user from loging onto the CSMS'>".$IMGUSERDEACTIVATE."Deactiate</a></td></tr>";
			}

		?>
	</table>


<h2>Unconfirmed / Deactivated Users</h2>
	<table>
		<tr class='table-heading'><th>Username</th><th>Email Address</th><th>Date of Registration</th></tr>
		<?php
		  	$query = "SELECT * FROM users WHERE active='0' ";
		  	$result = mysql_query($query);
			while($row = mysql_fetch_array($result))
				echo "<tr><td><a href='player.php?uid=" .$row['uid'] . "' title='Show details of this player'>".$IMGUSER." " . $row['username'] . "</a></td><td>" . $row['email'] . "</td><td>" . $row['registration-date'] . "</td><td><a href='?a=activate&amp;uid=".$row['uid']."' title='This will allow the user to log on to the CSMS'>".$IMGUSERACTIVATE." Activate</a></td><td><a href='?a=delete&amp;uid=".$row['uid']."' title='This will delete the user account.'>".$IMGUSERDELETE." Delete</a></td></tr>";
		?>
	</table>


<h2>Homeless Characters</h2>
	<p><a href='new_character.php' title='Create a new character. It will not be associated with a player'><?php echo $IMGCHARADD; ?> Create Character</a></p>
	<table>
		<tr class='table-heading'><th>Character Name</th></tr>
		<?php
				$query2 = "SELECT characters.uid,characters.name FROM characters LEFT JOIN users_characters ON (characters.uid=users_characters.character_record) WHERE users_characters.user IS NULL;";
				$result2 = mysql_query($query2);
				while($row = mysql_fetch_array($result2))
					echo "<tr><td><a href='character.php?uid=" .$row['uid'] . "' title='Show details of this character'>".$IMGCHAR." " . $row['name'] . "</a></td><td><a href='associate.php?char=".$row['uid'] . "' title='Associate this character with a player.'>".$IMGCHARASSOCIATE."Associate</a></td><td><a href='?a=delchar&amp;charid=".$row['uid'] . "' title='Perminantly delete this character'>".$IMGCHARDELETE."Delete</a></td></tr>";
		?>

	</table>


<h2>All Characters</h2>
	<table>
		<tr class='table-heading'><th>Character Name</th></tr>
		<?php
		  	$result = mysql_query("SELECT * FROM characters");
			while($row = mysql_fetch_array($result))
				echo "<tr><td><a href='character.php?uid=" .$row['uid'] . "' title='Show details of this characte'>".$IMGCHAR." " . $row['name'] . "</a></td><td><a href='associate.php?char=".$row['uid'] . "' title='Associate this character with a player'>".$IMGCHARASSOCIATE."Associate</a></td></tr>";
		?>
	</table>
</div>

<?php include "../footer.php"; ?>
