<?php
	session_start();

	/* If the user isn't logged in, send them to the login page. */
	if (!isset($_SESSION['user']))
	{
		header("location:index.php?logout=1");
		die();
	}

	/* Sanitize session data and get user data from database. We'll probably need it. */
	include "../dbconnect.php";
	if (function_exists('sanitize'))
	{
		$_SESSION = sanitize($_SESSION);
		$result = mysql_query("SELECT * FROM users WHERE uid = '" . $_SESSION['user'] . "'");
		$user = sanitize(mysql_fetch_array($result));
	}
	else
	{
		echo "<div class='error'><span class='title'>Server Error</span> Uh... Something went wrong. If it happens again, shout at us until we fix it.</div>";
		die();
	}
	
	
	if ($user['images'] != '0')
	{
		if ($user['images'] == 1)
			$prefix ="<img alt='' src='images16/";
		else if ($user['images'] == 2)
			$prefix ="<img alt='' src='images32/";
		else if ($user['images'] == 3)
			$prefix ="<img alt='' src='images48/";
		else 
		{
			$prefix ="<img src='images16/";
			echo "<div class='error'><span class='title'>Data Error</span>To remove this error, please reset your image preferences.</div>";
		}
	}
	
	function showAdminMenu()
	{
		
	}

?>