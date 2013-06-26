<?php 
	$PAGENAME = "Downtime";
	$PAGETITLE= "Compose your downtime";
	include "../header.php";
	include "../func.inc.php";
	include "../menu.php";

	$uid = intval($_GET['uid']);
	$query = "SELECT * FROM `downtime` WHERE `uid`='".$uid."';";
	$result = mysql_query($query);
	$downtime = mysql_fetch_array($result);

				$queryplayer = "SELECT uid, username FROM users WHERE uid='".$downtime['player']."' ";
		  		$resultplayer = mysql_query($queryplayer);
				$tehplayer = mysql_fetch_array($resultplayer);
				$querycharacter = "SELECT name FROM characters WHERE uid='".$downtime['character']."' ";
		  		$resultcharacter = mysql_query($querycharacter);
				$tehcharacter = mysql_fetch_array($resultcharacter);	



    $query = "SELECT UNIX_TIMESTAMP(`downtimedeadline`) as ddeadline, UNIX_TIMESTAMP(`returndeadline`) as rdeadline FROM `csms_settings` WHERE uid='1'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $deadline = $row['ddeadline'];
    $returndeadline = $row['rdeadline'];


	if (isset($_GET['a']))
	{
		if ($_GET['a'] == "new")
		{

			$query = "SELECT * FROM downtime WHERE `character` = '" .intval($_GET['char'])."' AND handled = '0'";
  			$result = mysql_query($query);
			if (mysql_num_rows($result) ==0)
			{
			    $query = "INSERT INTO `downtime` SET `player`='".$user['uid']."',`character`='".intval($_GET['char'])."';";# At the moment you can create downtimes for charactrers you dont own.
			    $result = mysql_query($query);
			    header("location:downtime.php?uid=".mysql_insert_id());			  
			    die();
			}

			header("location:character.php?uid=" .intval($_GET['char']));	
			die();
		}
 
	    header("location:player.php");
	    die();
	}

	if (isset($_GET['setlight']))
	{
	    if ($_GET['setlight'] == "green")
	    {
		$query = "UPDATE `downtime` SET `greenlight`='2' WHERE uid='".intval($_GET['uid'])."';";
		$result = mysql_query($query);
	    }
	    if ($_GET['setlight'] == "orange")
	    {
		$query = "UPDATE `downtime` SET `greenlight`='1' WHERE uid='".intval($_GET['uid'])."';";
		$result = mysql_query($query);
	    }
	    if ($_GET['setlight'] == "red")
	    {
		$query = "UPDATE `downtime` SET `greenlight`='0' WHERE uid='".intval($_GET['uid'])."';";
		$result = mysql_query($query);
	    }
	    header("location:?uid=".$downtime['uid']);
	}

	if (isset($_POST['response']))
	{
	  if ($user['admin'])
	    {
		$responsetext = sanitize($_POST['response']);
		$query = "UPDATE `downtime` SET `response`='".$responsetext."' WHERE uid='".intval($_GET['uid'])."' ;";
		$result = mysql_query($query);
		header("location:?uid=".$downtime['uid']);
	    }
	}

	if (isset($_POST['downtime']))
	{
		$downtimetext = sanitize($_POST['downtime']);
		$query = "UPDATE `downtime` SET `downtime`='".$downtimetext."' WHERE uid='".intval($_GET['uid'])."' AND `locked` = 0;";
		$result = mysql_query($query);
		header("location:?uid=".$downtime['uid']);
	}

	/* If we're passed the return deadline, and the downtime has been greenlighted, set it to handled. */
	$now = new DateTime('now');
	if ((!$downtime['handled']) and ($now->format('U') > $returndeadline) and ($downtime['greenlight'] == '2'))
	{
	    $query = "UPDATE `downtime` SET `handled` = '1' WHERE uid = '".$downtime['uid']."';";
	    mysql_query($query);
	    header("location:?uid=".$downtime['uid']);
	}

	/* If it's not already locked, and we're past the submission deadline, lock the downtime */
	if (($now->format('U') > $deadline) and ($downtime['locked'] == '0'))
	{
	    $query = "UPDATE `downtime` SET `locked` = '1' WHERE uid = '".$downtime['uid']."';";
	    mysql_query($query);
	    header("location:?uid=".$downtime['uid']);
	}
	echo "<h2>Downtime</h2>";
	echo "<p><a href='player.php?uid=". $downtime['player'] ."'>".$IMGUSER." ".$tehplayer['username']."</a><br /><a href='character.php?uid=". $downtime['character'] ."'>".$IMGCHAR."".$tehcharacter['name'] ."</a><br />Created: ".$downtime['time'] ."</p>";


	if (($user['admin']) or (($downtime['handled']) and ($downtime['greenlight'] =='2')))
	{
		echo "<h2>Reply</h2><p>".nl2br($downtime['response'])."</p>";
	}

	if ($user['admin'])
	{
		if (($downtime['handled']) and ($downtime['greenlight'] =='2'))
		    echo "<p><strong>This is visible to the player.</strong></p>";
		else
		    echo "<p><strong>This is currently hidden to the player.</strong></p>";

		if ($downtime['greenlight'] != 2)
		  echo "	<form action='downtime.php?uid=".$downtime['uid']."' method='post'>
			  <p><textarea name='response' rows='20' cols='30'>" . $downtime['response'] ."</textarea></p>
			  <p><input type='submit' value='Update' /></p>
			</form>";


		if ($downtime['greenlight'] == '0')
			echo $IMGREDLIGHT."<a href='?uid=".$downtime['uid']."&amp;setlight=orange' title='Downtimes should be set to orange when a response has been written, or has been partially written, but has not been confirmed.'>Set to Orange</a>";
		else if ($downtime['greenlight'] == '1')
			echo $IMGORANGELIGHT ."<a href='?uid=".$downtime['uid']."&amp;setlight=red' title='Downtimes should be red if no response has been written in all or in part.'>Set to Red</a><br /><a href='?uid=".$downtime['uid']."&amp;setlight=green' title='A downtime that has been greenlighted will be visible to the player after the return deadline has passed. Only when downtimes are finished and confirmed should they be greenlighted.'>Set to Green</a>";
		else if ($downtime['greenlight'] == '2')
			echo $IMGGREENLIGHT."<a href='?uid=".$downtime['uid']."&amp;setlight=orange' title='Downtimes should be set to orange when a response has been written, or has been partially written, but has not been confirmed.'>Set to Orange</a>";
	}

	echo "<h2>Downtime</h2><p>".nl2br($downtime['downtime'])."</p>";


	if ($user['admin'])
	{
		/* Display to the admin whether the player can or can not edit the downtime. */
		 if ($downtime['locked'] == '0')
		    echo "<p><strong>The player can still edit this downtime.</strong></p>";
		else
		    echo "<p><strong>The player can no longer edit this downtime.</strong></p>";

	}

	if ( ($user['uid'] == $tehplayer['uid']) and ($downtime['locked'] == '0'))
	{

		/* Only display the edit form to the owner of the downtime. Only display it if before the deadline. */
		echo "		<form action='downtime.php?uid=".$downtime['uid']."' method='post'>
					<p><textarea name='downtime' rows='20' cols='30'>" . $downtime['downtime'] ."</textarea></p>
					<p><input type='submit' value='Update' /></p>
				</form>";
	}

?>

</div>

<?php include "../footer.php"; ?>
