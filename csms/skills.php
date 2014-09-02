<?php 
	$PAGENAME = "Skills Manager";
	$PAGETITLE= "Add and update skills";

	include "../func.inc.php";
	include "../session.php";

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
		print "<p>Server Error.</p>";
		die();
	}

	/* Handle Admin actions */
	if (isset($_GET['a']))
	{
		if ($_GET['a'] == "add")
		{
			$query = "INSERT INTO `skills` (`name` ,`description` ,`order`,`cost`,`prerequisites`) VALUES ('New Skill', '', '1000','0','None');";
			$result = mysql_query($query);

			$u = mysql_insert_id();

			$editCharacters = "ALTER TABLE `characters` ADD `skill".$u."` BOOLEAN NOT NULL;";
			$result = mysql_query($editCharacters);
			header("location:edit_skill.php?uid=".$u);
		}

		if ($_GET['a'] == "edit")
		{
			$uid= intval($_GET['uid']);
			header("location:edit_skill.php?uid=$uid");
		}
	}

  include "../header.php";
  include "../menu.php";
?>

<h2>List of skills</h2>
<a href="?a=add" title="This will add a new skill to the system. Do not do this if there are unwanted skills already existing, as it is better to rename them"><?php echo $IMGSKILLADD; ?> Add a Skill</a>

	<table>

<?php
  	$query = "SELECT * FROM skills ORDER BY `order`";
  	$result = mysql_query($query);
	echo "<tr class='table-heading'><th>Name</th><th>Teaser</th><th>Description</th><th>Cost</th><th>Prerequisites</th><th>Ref Notes</th><th>Order</th></tr>";
	while($row = mysql_fetch_array($result))
	{
		echo "<tr><td><a href='?a=edit&amp;uid=".$row['uid']."' title='This will allow you to edit this skill.'>".$IMGSKILL."" . $row['name'] . "</a></td><td>" . $row['teaser'] . "</td><td>" . $row['description'] . "</td><td>" . $row['cost'] . "</td><td>" . $row['prerequisites'] . "</td><td>" . $row['refnotes'] . "</td><td>" . $row['order'] . "</td><td><a href='?a=edit&amp;uid=".$row['uid']."' title='This will allow you to edit this skill.'>".$IMGSKILLEDIT."Edit</a></td></tr>";
	}

?>

</table>


</div>

<?php include "../footer.php"; ?>
