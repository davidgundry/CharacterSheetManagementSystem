<?php 
	$PAGENAME = "Downtimes Manager";
	$PAGETITLE= "Manage submitted downtimes";

	include "../func.inc.php";
	include "../session.php";


	if ($user['admin'] != 1)
	{
		header("location:logout.php");
		die();
	}

	/* Sanitize GET and POST input */
	if (function_exists('sanitize'))
	{
		$_GET = sanitize($_GET);
		$_POST = sanitize($_POST);
	}
	else
	{
		print "<p>Server Error.</p>";
		die();
	}

	/* Handle Admin actions */
	if (isset($_GET['a']))
	{
		if ($_GET['a'] == "deactivate")
		{
			$query = "UPDATE users SET active='0' WHERE uid='".$_GET['uid']."' ";
			$result = mysql_query($query);
		}
	}

	/* Update submission deadline if set */
	if (isset($_POST['deadline']))
	{
	    $timestamp = $_POST['deadline'];
	    $query = "UPDATE `csms_settings` SET `downtimedeadline`='".$timestamp."' WHERE uid='1';";
	    mysql_query($query);
	}

	/* Update return deadline if set */
	if (isset($_POST['returndeadline']))
	{
	    $timestamp = $_POST['returndeadline'];
	    $query = "UPDATE `csms_settings` SET `returndeadline`='".$timestamp."' WHERE uid='1';";
	    mysql_query($query);
	}

	/* Get deadlines from database */
	$query = "SELECT UNIX_TIMESTAMP(`downtimedeadline`) as ddeadline, UNIX_TIMESTAMP(`returndeadline`) as rdeadline FROM `csms_settings` WHERE uid = '1';";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$deadline = $row['ddeadline'];
	$returndeadline = $row['rdeadline'];
    
    	include "../header.php";
	include "../menu.php";
?>

<div class='floatingbox'>
	<h2>Downtime Deadlines</h2>
		<form action="downtime_list.php" method="post">
			<p>Current downtime deadline is:<?php echo date('Y-m-d H:i:s',$deadline); ?>.<br /> <input name='deadline' type='text' value='<?php echo date('Y-m-d H:i:s',$deadline); ?>' /><input type="submit" value="Update" /></p>
		</form>
		<form action="downtime_list.php" method="post">
			<p>Current response deadline is <?php echo date('Y-m-d H:i:s',$returndeadline); ?>. <br /> <input name='returndeadline' type='text' value='<?php echo date('Y-m-d H:i:s',$returndeadline); ?>' /><input type="submit" value="Update" /></p>
		</form>
</div>

<h2>Pending Downtimes</h2>
	<table>
		<?php
		  	$query = "SELECT * FROM downtime WHERE handled='0' ";
		  	$result = mysql_query($query);
			echo "<tr class='table-heading'><th>Date</th><th>Player</th><th>Character</th></tr>";
			while($downtime = mysql_fetch_array($result))
			{
				$queryplayer = "SELECT uid, username FROM users WHERE uid='".$downtime['player']."' ";
		  		$resultplayer = mysql_query($queryplayer);
				$tehplayer = mysql_fetch_array($resultplayer);
				$querycharacter = "SELECT uid, name FROM characters WHERE uid='".$downtime['character']."' ";
		  		$resultcharacter = mysql_query($querycharacter);
				$tehcharacter = mysql_fetch_array($resultcharacter);			
				echo "<tr><td><a href='downtime.php?uid=".$downtime['uid']."'>".$IMGDOWNTIMEPENDING." ".$downtime['time']."</a></td><td><a href='player.php?uid=".$tehplayer['uid']."'>". $IMGUSER." ".$tehplayer['username']."</a></td><td><a href='character.php?uid=".$tehcharacter['uid']."'>".$IMGCHAR."".$tehcharacter['name']."</a></td><td><a href='downtime.php?uid=".$downtime['uid']."'>". $IMGDOWNTIMEEDIT." Edit</a></td><td>";
			    if ($downtime['greenlight'] == '0')
				    echo $IMGREDLIGHT;
			    else if ($downtime['greenlight'] == '1')
				    echo $IMGORANGELIGHT;
			    else if ($downtime['greenlight'] == '2')
				    echo $IMGGREENLIGHT;
			    echo "</td></tr>";
			}

		?>
	</table>

<h2>Resolved Downtimes</h2>
	<table>
		<?php
		  	$query = "SELECT * FROM downtime WHERE handled='1' ORDER BY `time` DESC";
		  	$result = mysql_query($query);
			echo "<tr class='table-heading'><th>Date</th><th>Player</th><th>Character</th></tr>";
			while($downtime = mysql_fetch_array($result))
			{
				$queryplayer = "SELECT uid, username FROM users WHERE uid='".$downtime['player']."' ";
		  		$resultplayer = mysql_query($queryplayer);
				$tehplayer = mysql_fetch_array($resultplayer);
				$querycharacter = "SELECT uid, name FROM characters WHERE uid='".$downtime['character']."' ";
		  		$resultcharacter = mysql_query($querycharacter);
				$tehcharacter = mysql_fetch_array($resultcharacter);			
				echo "<tr><td><a href='downtime.php?uid=".$downtime['uid']."'>".$IMGDOWNTIME." ".$downtime['time']."</a></td><td><a href='player.php?uid=".$tehplayer['uid']."'>". $IMGUSER ." ".$tehplayer['username']."</a></td><td><a href='character.php?uid=".$tehcharacter['uid']."'>" .$IMGCHAR. "".$tehcharacter['name']."</a></td><td><a href='downtime.php?uid=".$downtime['uid']."'>".$IMGDOWNTIMEEDIT." Edit</a></td><td>";
			    if ($downtime['greenlight'] == '0')
				    echo $IMGREDLIGHT;
			    else if ($downtime['greenlight'] == '1')
				    echo $IMGORANGELIGHT;
			    else if ($downtime['greenlight'] == '2')
				    echo $IMGGREENLIGHT;
			    echo "</td></tr>";
			}

		?>
	</table>
</div>

<?php include "../footer.php"; ?>
