<?php 
	$PAGENAME = "Edit Skill";
	$PAGETITLE= "Edit the details of a skill";

	include "../func.inc.php";
	include "../session.php";

	if ($user['admin'] != 1)
	{
		header("location:logout.php");
		die();
	}

	$uid = intval($_GET['uid']);

	if (isset($_POST['name']))
	{
		$uidpost = intval($_POST['uid']);
		$order = intval($_POST['order']);
		$name = sanitize($_POST['name']);
		$teaser = sanitize($_POST['teaser']);
		$desc = sanitize($_POST['desc']);
		$refnotes = sanitize($_POST['refnotes']);
		$query = "UPDATE `skills` SET `name` = '".$name."', `description` = '".$desc."', `order` = '".$order."', `teaser`='".$teaser."', `refnotes`='".$refnotes."' WHERE `uid` = '".$uidpost."';";
		$result = mysql_query($query);
		header("location:skills.php");
	}

	include "../header.php";
	include "../menu.php";
	
?>
		<?php
			$query = "SELECT * FROM skills where uid='$uid' LIMIT 1";
	  		$result = mysql_query($query);
			while($row = mysql_fetch_array($result))
			{
				echo "<h2>Preview Skill</h2>
					<table><tr><th>Skill Name</th><th>Skill Description</th></tr><tr><td>".$IMGSKILL." ".$row['name']."</td><td>".$row['description']."</td></tr></table>";

					echo "	<h2>Skill Details</h2>
	<p>This will edit this skill. You can also change the order the skill appears on character sheets in by changing its 'order'.</p>
	<form action='edit_skill.php' method='post'>";
					echo "<p><input type='hidden' name='uid' value='".$uid."' />Order: <input name='order' type='text' value='" . $row['order'] . "' /><br />";
					echo "Name:<input name='name' type='text' value='" . $row['name'] . "' /><br />";
					echo "Teaser:<br /><textarea class='smalltextarea' rows='10' cols='10' name='teaser'>". $row['teaser'] . "</textarea><br />";
					echo "Description:<br /><textarea class='smalltextarea' rows='10' cols='10' name='desc'>". $row['description'] . "</textarea><br />";
					echo "Ref Notes:<br /><textarea class='smalltextarea' rows='10' cols='10' name='refnotes'>". $row['refnotes'] . "</textarea></p>";
			}	  		
		?> 
		<p><input type="submit" value="Edit" /></p>
	</form>

<h2>Characters with this skill </h2>
<table>
	<tr class='table-heading'><td>Character</td></tr>
		<?php
			$query = "SELECT * FROM characters where skill".$uid."='2'";
	  		$result = mysql_query($query);
			while($row = mysql_fetch_array($result))
			{
					echo "<tr><td><a href='character.php?uid=".$row['uid'] . "'>".$IMGCHAR." " .$row['name']."</a></td></tr>";
			}	  		
		?> 
</table>

<h2>Characters who can learn this skill </h2>
<table>
	<tr class='table-heading'><td>Character</td></tr>
		<?php
			$query = "SELECT * FROM characters where skill".$uid."='1'";
	  		$result = mysql_query($query);
			while($row = mysql_fetch_array($result))
			{
					echo "<tr><td><a href='character.php?uid=".$row['uid'] . "'>".$IMGCHAR." ".$row['name']."</a></td></tr>";
			}	  		
		?> 
</table>

</div>
<?php include "../footer.php"; ?>
