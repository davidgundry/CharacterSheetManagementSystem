<div id="headbar">

	<?php echo "<p>You are logged in as <a href='player.php' title='Show your user page'>". $user['username']. "</a></p>"; ?>

	<ul>
		<?php 
			if ($user['admin'] == 1)
			{
				echo "<li><a href='news.php' title='Add or delete news posts'>".$IMGNEWS."News</a></li>";
				echo "<li><a href='skills.php' title='Add or edit skills'>".$IMGSKILL."Skills Manager</a></li>";
				echo "<li><a href='downtime_list.php' title='Read and reply to submitted downtimes'>".$IMGDOWNTIME."Downtimes Manager</a></li>";
				echo "<li><a href='player_list.php' title='List of players and characters'>".$IMGUSER."Administration Panel</a></li>";
			}
		?>
		<li><a href='player.php' title='Details of your characters'><?php echo $IMGCHAR;?> Home</a></li>
		<li><a href="settings.php" title='Change your user settings'><?php echo $IMGSETTINGS;?> Settings</a></li>
		<li><a href="logout.php" title='Log out from the CSMS'><?php echo $IMGLOGOUT; ?> Logout</a></li>
	</ul>

</div>
