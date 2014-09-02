<?php 
	$PAGENAME= "Your Settings";
	$PAGETITLE = "Change your user settings";
	
	include "../func.inc.php";
	include "../session.php";

	if (isset($_GET['images']))
	{
		$images = intval($_GET['images']);
		$query = "UPDATE users SET images='".$images."' WHERE uid='". $user['uid']. "' ";
		$result = mysql_query($query);
		header("location:?");
	}

	$error = 0;
	
	/* If the player has submited a changed email address. */
	if (isset($_POST['email']))
	{	
		$email = $_POST['email'];
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	  	{
			$error = 1;
	  	}
		/* Sanitize new email and update the database. */
		else if (function_exists('sanitize'))
		{
			$newEmail = sanitize($_POST['email']);
			$query = "UPDATE users SET email='".$newEmail."' WHERE uid='". $user['uid']. "' ";
			$result = mysql_query($query);
			header("location:?es=1");
		}
		else
		{
			print "<div class='error'><span class='title'>Server Error</span></div>";
		}
	}
	
	/* If the player has submited a new password */
	if ((isset($_POST['newpass'])) and (isset($_POST['oldpass'])) and isset($_POST['newpassc']))
	{
		if (($_POST['newpass'] != '') and ($_POST['newpass'] == $_POST['newpassc']))
		{
			$oldpass = md5($_POST['oldpass']);
			$query = "SELECT uid FROM users WHERE uid = '".$user['uid']."' AND password = '$oldpass' LIMIT 1";
			$result = mysql_query($query);
			if(mysql_num_rows($result)==1)
			{
				$newpass = md5($_POST['newpass']);
				$query = "UPDATE users SET password='".$newpass."' WHERE uid='". $user['uid']. "' ";
				$result = mysql_query($query);
				header("location:?ps=1");

			} else
				$error = 2;
		}
		else
			$error = 3;
	}
	
	//if (function_exists('sanitize'))
	//{
	//	$_SESSION = sanitize($_SESSION);
	//	$result = mysql_query("SELECT * FROM users WHERE uid = '" . $_SESSION['user'] . "'");
	//	$user = sanitize(mysql_fetch_array($result));
	//}
	//else
	//{
	//	print "<div class='error'><span class='title'>Server Error</span></div>";
	//	die();
	//}
	
	include "../header.php";
	include "../menu.php";
	
	if ($error == 1)
		echo "<div class='error'><span class='title'>Invalid email address</span>The email address supplied is invalid.</div>";
	else if ($error == 2)
		echo "<div class='error'><span class='title'>Invalid password</span>Your old password was not recognised.</div>";
	else if ($error == 3)
		echo "<div class='error'><span class='title'>Details were not changed</span>Your password has not been updated. Either you did not enter a new password, or the passwords you entered did not match.</div>";
	
	if (isset($_GET['es']))
	{
	  echo "<div class='notification'><span class='title'>Email changed</span>Your email address has been updated.</div>";
	}
	if (isset($_GET['ps']))
	{
	  echo "<div class='notification'><span class='title'>Password changed</span>Your password has been updated.</div>";
	}
?>
<p>Registered on <span class='time'><?php echo $user['registration-date']; ?></span></p>
		<form action="settings.php" method="post">
			<p>Username: <?php echo $user['username']; ?><br />
			<label for="email">Email:</label> <input id="email" name="email" type="text" value="<?php echo $user['email']; ?>" /></p>
			<p><input type="submit" value="Update" /></p>
		</form>

		<form action="settings.php" method="post">
			<p><label for="oldpass">Current Password:</label> <input id="oldpass" name="oldpass" type="password" value="" /><br />
			<label for="newpass">New Password:</label> <input id="newpass" name="newpass" type="password" value="" /><br />
			<label for="newpassc">New Password Confirm:</label> <input id="newpassc" name="newpassc" type="password" value="" /></p>

			<p><input type="submit" value="Update" /></p>
		</form>
		
		<p>Images: <a href="?images=0">Off</a> <a href="?images=1">16x16</a> <a href="?images=2">32x32</a> <a href="?images=3">48x48</a></p>
	</div>
<?php include "../footer.php"; ?>
