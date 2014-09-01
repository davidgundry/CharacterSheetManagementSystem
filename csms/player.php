<?php 
  	$PAGENAME = "Character Sheets";
  	$PAGETITLE= "Your user details";
	include "../func.inc.php";
	include "../session.php";
	include "../header.php";
	include "../menu.php";

	/* Sanitize all $_GET input. */
	if (function_exists('sanitize'))
		$_GET = sanitize($_GET);
	else
	{
		print "<div class='error'><span class='title'>Server Error</span>Uh... Something went wrong. If it happens again, shout at us until we fix it.</div>";
		die();
	}

	if ($user['admin'] == 1)
	{
		if (isset($_GET['a']))
		{
			if ($_GET['a'] == "hide")
			{
				$query = "UPDATE users_characters SET visible='0' WHERE user = '". $_GET['uid'] ."' AND character_record='".$_GET['char']."' ";
				$result = mysql_query($query);
			}

			if ($_GET['a'] == "show")
			{
				$query = "UPDATE users_characters SET visible='1' WHERE user = '". $_GET['uid'] ."' AND character_record='".$_GET['char']."' ";
				$result = mysql_query($query);
			}

			if ($_GET['a'] == "disassociate")
			{
				$query = "DELETE FROM users_characters WHERE user = '". $_GET['uid'] ."' AND character_record='".$_GET['char']."' ";
				$result = mysql_query($query);
			}
		}
	}
	
  	if (($user['admin'] == 1) and (isset($_GET['uid'])))
	{
	  	$query = "SELECT * FROM users WHERE uid = '" . $_GET['uid'] ."'";
	  	$result = mysql_query($query);
	  	$usertoshow = mysql_fetch_array($result);
		echo "<h2>Viewing User ". $usertoshow['username'] ."</h2>";
	}
  	else
	{
	  	$usertoshow = $user;
	  	echo "<h2>Welcome Back, ". $user['username'] .". Your Characters:</h2>";
	}
?>

<div class='floatingbox'><p><strong>Username:</strong> <?php echo $usertoshow['username']; ?><br />Registered on <?php echo $usertoshow['registration-date']; ?> <br /> <strong>Email:</strong> <?php echo $usertoshow['email']; ?></p><p>Update your details on the <a href="settings.php">settings page.</a></p></div>

<?php
  	if (($user['admin'] == 1) and (isset($_GET['uid'])))
	{
		echo "<p><a href='new_character.php?user=".$usertoshow['uid']."' title='This will create a new character and associate it with ".$usertoshow['username']."''>".$IMGCHARADD."Create Character</a><br /><a href='associate.php?user=".$usertoshow['uid']."' title='Allows you to associate a pre-existing character with ".$usertoshow['username']."''>".$IMGCHARASSOCIATE."Associate Character</a></p>";
	}
?>

<h2>List of Characters</h2>

<table>
	<tr class='table-heading'><td>Character Name</td></tr>

	<?php

	  $query = "SELECT * FROM users_characters WHERE user = '" . $usertoshow['uid'] . "' AND visible = '1' ";
	  $result = mysql_query($query);
	  if(mysql_num_rows($result) > 0)
	  {
		  while($row = mysql_fetch_array($result))
		  {
			$query2 = "SELECT * FROM characters WHERE uid = '" . $row['character_record'] ."' LIMIT 1";
			$result2 = mysql_query($query2);

			while($character = mysql_fetch_array($result2))
			{
				if (($user['admin'] == 1) and (isset($_GET['uid'])))
				{
					echo "<tr><td><a href='character.php?uid=" .$character['uid'] . "'>".$IMGCHAR." " . $character['name'] . "</a></td><td><a href='?a=hide&char=".$character['uid']."&uid=".$usertoshow['uid']."' title='This will make the character invisible to ".$usertoshow['username']."'>". $IMGCHARHIDE ." Make Invisible</a></td></tr>";
				}
				else
				{
					echo "<tr><td><a href='character.php?uid=" .$character['uid'] . "'>".$IMGCHAR." " . $character['name'] . "</a></td></tr>";
				}
			}  
		  }
	  }
	  else
	  {
			if (($user['admin'] == 1) and (isset($_GET['uid'])))
			{
				echo "<p>This player has no associated character sheets.</p>";
			}
			else
			{
				echo "<p>You have no character sheets. You should contact the <a href='mailto:". $admin_email ."'>Admins</a>.</p>";
			}
	  }

	?>

</table>

<?php

if (($user['admin'] == 1) and (isset($_GET['uid'])))
{
	echo "<h2>Hidden Characters</h2><table><tr class='table-heading'><td>Character Name</td></tr>";
	$query = "SELECT * FROM users_characters WHERE user = '" . $usertoshow['uid'] . "' AND visible = '0' ";
  	$result = mysql_query($query);
	while($row = mysql_fetch_array($result))
	{
		$query2 = "SELECT * FROM characters WHERE uid = '" . $row['character_record'] ."' LIMIT 1";
		$result2 = mysql_query($query2);

		while($character = mysql_fetch_array($result2))
		{
			echo "<tr><td><a href='character.php?uid=" .$character['uid'] . "'>".$IMGCHAR." " . $character['name'] . "</a></td><td><a href='?a=show&char=".$character['uid']."&uid=".$usertoshow['uid']."' title='This will make the character visible to ".$usertoshow['username']."'>".$IMGCHARSHOW." Make Visible</a></td><td><a href='?a=disassociate&char=".$character['uid']."&uid=".$usertoshow['uid']."' title='This will disassociate this character with this player. To delete the character you will need to go to the Homeless Characters section.'>".$IMGCHARHIDE." Disassociate</a></td></tr>";
		}
	}
	echo "</table>";
}

?>

</div>

<?php include "../footer.php"; ?>
