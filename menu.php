<?php 	
/* Set the location of the images to use, depending on the user's preferred image size*/
	$IMGREDLIGHT = "<img alt='' src='images16/redlight.png' />";
	$IMGORANGELIGHT = "<img alt='' src='images16/orangelight.png' />";
	$IMGGREENLIGHT = "<img alt='' src='images16/greenlight.png' />";

	if ($user['images'] != '0')
	{
	$IMGSETTINGS  = $prefix."settings.png' />";
	$IMGLOGOUT = $prefix."logout.png' />";

	$IMGSKILL = $prefix."skill.png' />";
	$IMGSKILLCHECK = $prefix."skill_check.png' />";
	$IMGSKILLHIDE = $prefix."skill_hide.png' />";
	$IMGSKILLDELETE = $prefix."skill_delete.png' />";
	$IMGSKILLEDIT = $prefix."skill_edit.png' />";
	$IMGSKILLADD = $prefix."skill_add.png' />";

	$IMGUSER = $prefix."user.png' />";
	$IMGUSERACTIVATE = $prefix."user_activate.png' />";
	$IMGUSERDELETE = $prefix."user_delete.png' />";
	$IMGUSERDEACTIVATE = $prefix."user_deactivate.png' />";

	$IMGCHAR = $prefix."vcard.png' />";
	$IMGCHARASSOCIATE = $prefix."vcard_forward.png' />";
	$IMGCHARSHOW = $prefix."vcard_check.png' />";
	$IMGCHARHIDE = $prefix."vcard_download.png' />";
	$IMGCHARADD = $prefix."vcard_add.png' />";
	$IMGCHARDELETE = $prefix."vcard_delete.png' />";

	$IMGDOWNTIME = $prefix."downtime.png' />";
	$IMGDOWNTIMEPENDING = $prefix."downtime_pending.png' />";
	$IMGDOWNTIMEADD = $prefix."downtime_add.png' />";
	$IMGDOWNTIMEEDIT = $prefix."downtime_edit.png' />";

	$IMGNEWS = $prefix."news.png' />";
	$IMGNEWSADD = $prefix."news_add.png' />";
	$IMGNEWSDELETE =$prefix."news_delete.png' />";
	} else
	{
		$IMGSETTINGS  = "";
	$IMGLOGOUT = "";

	$IMGSKILL = "";
	$IMGSKILLCHECK = "";
	$IMGSKILLHIDE = "";
	$IMGSKILLDELETE = "";
	$IMGSKILLEDIT = "";
	$IMGSKILLADD = "";

	$IMGUSER = "";
	$IMGUSERACTIVATE = "";
	$IMGUSERDELETE = "";
	$IMGUSERDEACTIVATE = "";

	$IMGCHAR = "";
	$IMGCHARASSOCIATE = "";
	$IMGCHARSHOW = "";
	$IMGCHARHIDE = "";
	$IMGCHARADD = "";
	$IMGCHARDELETE = "";

	$IMGDOWNTIME = "";
	$IMGDOWNTIMEPENDING = "";
	$IMGDOWNTIMEADD = "";
	$IMGDOWNTIMEEDIT = "";

	$IMGNEWS = "";
	$IMGNEWSADD = "";
	$IMGNEWSDELETE = "";
	}
?>

<div id="headbar">

	<?php echo "<p>You are logged in as <a href='player.php' title='Show your user page'>". $user['username']. "</a></p>"; ?>

	<ul>
		<?php 
			if ($user['admin'] == 1)
			{
				echo "<li><a href='news.php' title='Add or delete news posts'>".$IMGNEWS."News</a></li>";
				echo "<li><a href='skills.php' title='Add or edit skills'>".$IMGSKILL."Skills Manager</a></li>";
				if ($DOWNTIMESENABLED)
					echo "<li><a href='downtime_list.php' title='Read and reply to submitted downtimes'>".$IMGDOWNTIME."Downtimes Manager</a></li>";
				echo "<li><a href='player_list.php' title='List of players and characters'>".$IMGUSER."Administration Panel</a></li>";
			}
		?>
		<li><a href='player.php' title='Details of your characters'><?php echo $IMGCHAR;?> Home</a></li>
		<li><a href="settings.php" title='Change your user settings'><?php echo $IMGSETTINGS;?> Settings</a></li>
		<li><a href="logout.php" title='Log out from the CSMS'><?php echo $IMGLOGOUT; ?> Logout</a></li>
	</ul>

</div>
