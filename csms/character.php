<?php 
	$PAGENAME = "Character Sheet";
	$PAGETITLE= "View your character sheet";
	include "../func.inc.php";
	include "../session.php";

if ($user['admin'] == 1)
	{
		if (function_exists('sanitize'))
		{
			if ((isset($_POST['name'])) and (isset($_POST['sheet'])))
			{
				$name = sanitize($_POST['name']);
				$sheet =  sanitize($_POST['sheet']);
				$uid = intval($_POST['uid']);
				$query = "UPDATE characters SET name='".$name."', sheet='".$sheet."' WHERE uid = '" . $uid . "'";
				$result = mysql_query($query);
				header("location:character.php?uid=".$uid);
			}

			if (isset($_GET['showskill']))
			{
				$uid = intval($_GET['uid']);
				$skilltogain = intval($_GET['showskill']);
				$query = "UPDATE characters SET skill".$skilltogain." = '1' WHERE uid = '" . $uid . "'";
				$result = mysql_query($query);
				header("location:character.php?uid=".$uid);
			}

			if (isset($_GET['delskill']))
			{
				$uid = intval($_GET['uid']);
				$skilltolose = intval($_GET['delskill']);
				$query = "UPDATE characters SET skill".$skilltolose." = '0' WHERE uid = '" . $uid . "'";
				$result = mysql_query($query);
				header("location:character.php?uid=".$uid);
			}

			if (isset($_GET['learnskill']))
			{
				$uid = intval($_GET['uid']);
				$skilltolearn = intval($_GET['learnskill']);
				$query = "UPDATE characters SET skill".$skilltolearn." = skill".$skilltolearn."+1 WHERE uid = '" . $uid . "'";
				$result = mysql_query($query);
				header("location:character.php?uid=".$uid);
			}

			if (isset($_GET['unlearnskill']))
			{
				$uid = intval($_GET['uid']);
				$skilltounlearn = intval($_GET['unlearnskill']);
				$query = "UPDATE characters SET skill".$skilltounlearn." = skill".$skilltounlearn."-1 WHERE uid = '" . $uid . "'";
				$result = mysql_query($query);
				header("location:character.php?uid=".$uid);
			}
		}
		else
		{
			print "<div class='error'><span class='title'>Server Error</span>Sanitization error.</div>";
		}

	}
	
  include "../header.php";
  include "../menu.php";
	
  $query = "SELECT uid FROM users_characters WHERE user = '" . $_SESSION['user'] . "' AND character_record = '". $_GET['uid'] ."' AND visible = '1' ";
  $result = mysql_query($query);

  if((mysql_num_rows($result) > 0) or ($user['admin'] == 1)) 
  {
	$query2 = "SELECT * FROM characters WHERE uid = '" . $_GET['uid'] ."' LIMIT 1;";
	$result2 = mysql_query($query2);

	if (mysql_num_rows($result2)==1)
	{
		while($character = mysql_fetch_array($result2))
		{
			echo "<h2>" . $character['name'] ."</h2>";
		 	echo nl2br($character['sheet']);

			/* the first skill is 1, go to the number of skills in the db */
			echo "<h2>Skills</h2><table><tr class='table-heading'><th>Skill Name</th><th>Quantity</th><th>Skill Description</th></tr>";

			$order = mysql_query("SELECT `uid` FROM `skills` ORDER BY `order`;");
			$tolearn = "";
			while($next = mysql_fetch_array($order))
				{
					$i = $next['uid'];
					if ($character['skill'.$i] >= '2')
					{
						$result3 = mysql_query("SELECT * FROM skills WHERE uid = '".$i."' LIMIT 1;");
						while($skill = mysql_fetch_array($result3))
						{
							$qty = intval($character['skill'.$i]) - 1;
							#if ($qty <= 1)
							#	$qty = 0;
							echo "<tr><td>".$IMGSKILL." ".$skill['name']."</td><td>". $qty ."</td><td>".$skill['description']."</td>";
							if ($user['admin'] == 1)
								echo "<td><a href='?uid=".$character['uid']."&learnskill=".$skill['uid']."'>".$IMGSKILLCHECK." Learn</a></td><td><a href='?uid=".$character['uid']."&unlearnskill=".$skill['uid']."'>".$IMGSKILLDELETE." Unlearn</a></td><td><a href='?uid=".$character['uid']."&delskill=".$skill['uid']."'>".$IMGSKILLHIDE." Hide</a></td>";
							echo "</tr>";
						}
					}



					if ($character['skill'.$i] == '1')
					{

						$result3 = mysql_query("SELECT * FROM skills WHERE uid = '".$i."' LIMIT 1;");
						while($skill = mysql_fetch_array($result3))
						{
							$tolearn = $tolearn . "<tr><td>".$IMGSKILL." Learnable: ".$skill['name']."</td><td>".$skill['teaser']."</td>";
							if ($user['admin'] == 1)
							{
								$tolearn = $tolearn . "<td><a href='?uid=".$character['uid']."&learnskill=".$skill['uid']."'>".$IMGSKILLCHECK." Learn</a></td><td><a href='?uid=".$character['uid']."&delskill=".$skill['uid']."'> ".$IMGSKILLHIDE." Hide</a></td>";
							}
							$tolearn = $tolearn . "</tr>";
						}
					}
				}
			echo "</table>";

			echo "<h2>Learnable Skills</h2><table><tr class='table-heading'><th>Skill Name</th><th>Skill Description</th></tr>".$tolearn."</table>";
			
			/* Display options to change things if you are an admin */
			if ($user['admin'] == 1)
			{
				/* List all those players the character is associated with */
				echo "<hr /><h2>Players associated with this Character</h2>";
				echo "<a href='associate.php?char=".$character['uid']."' title='Associate this character to a player'>".$IMGCHARASSOCIATE." Associate to new player</a>";
				/* get user ids ('user') which are associated with the character */
				$query2 = "SELECT * FROM users_characters WHERE character_record = '" . $character['uid'] ."'";
				$result2 = mysql_query($query2);
				if (mysql_num_rows($result2) == 0)
					echo "<p>This character is not associated with anyone!</p>";
				else
				{
					echo "<ul>";
					while($associateduser = mysql_fetch_array($result2))
					{
						/* list all those users who have that user id, limit 1 */
						$query3 = "SELECT * FROM users WHERE uid = '" . $associateduser['user'] ."' LIMIT 1";
						$result3 = mysql_query($query3);
						while($tehuser = mysql_fetch_array($result3))
							if ($associateduser['visible'])
							{
								echo "<li><a href='player.php?uid=".$tehuser['uid']."'>".$IMGUSER." ".$tehuser['username']."</a> - ". $IMGCHARSHOW." Visible</li>";
							}
							else
							{
								echo "<li><a href='player.php?uid=".$tehuser['uid']."'>".$IMGUSER." ".$tehuser['username']."</a> - ".$IMGCHARHIDE." Hidden</li>";
							}
					}
					echo "</ul>";
				}

				echo "<h2>Update Character Sheet</h2><form action='character.php' method='post'>
				<p>Character Name: <input type='text' name='name' value=\"" . $character['name'] ."\"></p>
				<textarea name='sheet' rows=30 cols=50>" . $character['sheet'] ."</textarea>
				<input type='hidden' name='uid' value='" . $_GET['uid'] ."'>
				<p><input type='submit' value='Update'></p>
				</form>";

				echo "<table><tr class='table-heading'><th>Skill Name</th><th>Teaser Description</th><th>Skill Description</th><th>Ref Notes</th></tr>";
				$allskills = mysql_query("SELECT * FROM skills ORDER BY `order`");
				while($skill = mysql_fetch_array($allskills))
				{
					echo "<tr id='skill".$skill['uid']."'><td>".$IMGSKILL." ".$skill['name']."</td><td>".$skill['teaser']."</td><td>".$skill['description']."</td><td>".$skill['refnotes']."</td>";
					if ($character['skill'.$skill['uid']]  == '0')
						echo "<td><a href='?uid=".$character['uid']."&showskill=".$skill['uid']."#skill".$skill['uid']."'>".$IMGSKILLCHECK." Show </a></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
					else if ($character['skill'.$skill['uid']]  == '1')
						echo "<td>&nbsp;</td><td><a href='?uid=".$character['uid']."&learnskill=".$skill['uid']."#skill".$skill['uid']."'>".$IMGSKILLCHECK." Learn </a></td><td>&nbsp;</td><td><a href='?uid=".$character['uid']."&delskill=".$skill['uid']."#skill".$skill['uid']."'>".$IMGSKILLHIDE." Hide</a></td>";
					else if ($character['skill'.$skill['uid']] >= '2')
						echo "<td>&nbsp;</td><td>&nbsp;</td><td><a href='?uid=".$character['uid']."&unlearnskill=".$skill['uid']."#skill".$skill['uid']."'>".$IMGSKILLDELETE." Unlearn </a></td><td><a href='?uid=".$character['uid']."&delskill=".$skill['uid']."#skill".$skill['uid']."'>".$IMGSKILLHIDE." Hide</a></td>";
										echo "</tr>";
				}
				echo "</table>";


			}

			/* Show players their downtimes */
			$query = "SELECT * FROM downtime WHERE `character` = '" . $character['uid'] . "' AND handled = '0'";
  			$result = mysql_query($query);

			echo "<h2>Pending Downtimes</h2><table><tr class='table-heading'><th>Date</th><th>Downtime</td></tr>";
			while ($downtime = mysql_fetch_array($result))
			{
				echo "<tr><td><a href='downtime.php?uid=".$downtime['uid']."'>".$IMGDOWNTIMEPENDING." ".$downtime['time']."</a></td><td>". substr($downtime['downtime'], 0, 100)."...</td><td>";
				if (!$downtime['locked'])
				    echo " <a href='downtime.php?uid=".$downtime['uid']."'>".$IMGDOWNTIMEADD." Edit</a></td>";
				echo "</tr>";
			}

			echo "</table>";
			if (mysql_num_rows($result) == 0)
			    echo "<a href='downtime.php?a=new&char=".$character['uid']."'>".$IMGDOWNTIMEADD." New Downtime</a>";

			$query = "SELECT * FROM downtime WHERE `character` = '" . $character['uid'] . "' AND handled = '1' AND greenlight='2'";
  			$result = mysql_query($query);

			echo "<h2>Processed Downtimes</h2><table><tr class='table-heading'><th>Date</th><th>Downtime</th><th>Response</th></tr>";
			while ($downtime = mysql_fetch_array($result))
			{
				echo "<tr><td><a href='downtime.php?uid=".$downtime['uid']."'>".$IMGDOWNTIME." ".$downtime['time']."</a></td><td>".substr($downtime['downtime'], 0, 100)."...</td><td>".substr($downtime['response'], 0, 100)."...</td></tr>";
			}

			echo "</table>";
		}  
	}
	else
	{
		/* If we couldn't find the character, apologise. */
		echo "<div class='error'><span class='title>Missing Character</span>This character doesn't appear to exist. Something has gone wrong somewhere. Sorry about that.</div>";
	}
  }
  else
  {
	echo "<div class='error><span class='title'>Permission Error</span>You do not have permission to view that character.</div>";
  }

			echo "</div>";

include "../footer.php";
?>
