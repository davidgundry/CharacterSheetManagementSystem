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

	include "../header.php";
	include "../menu.php";
?>
<p>Registered on <span class='time'><?php echo $user['registration-date']; ?></span></p>
<?php

	/* If the player has submited a changed email address. */
	if (isset($_POST['email']))
	{	
		$email = $_POST['email'];
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
	  	{
			print "<div class='error'><span class='title>Invalid email address</span>The email address supplied is invalid.</div>";
	  	}
		/* Sanitize new email and update the database. */
		else if (function_exists('sanitize'))
		{
			$newEmail = sanitize($_POST['email']);
			$query = "UPDATE users SET email='".$newEmail."' WHERE uid='". $user['uid']. "' ";
			$result = mysql_query($query);
		}
		else
			print "<div class='error'><span class='title'>Server Error</span></div>";
	}
	
	/* If the player has submited a new password */
	if ((isset($_POST['newpass'])) and (isset($_POST['oldpass'])) and isset($_POST['newpassc']))
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
				echo "<div class='notification'><span class='title'>Password changed</span>Your password has been updated.</div>";

			} else
				echo "<div class='error'><span class='title'>Invalid password</span>Your old password was not recognised.</div>";
		}
		else
			echo "<div class='error'><span class='title'>Details were not changed</span>Your password has not been updated. Either you did not enter a new password, or the passwords you entered did not match.</div>";

	if (function_exists('sanitize'))
	{
		$_SESSION = sanitize($_SESSION);
		$result = mysql_query("SELECT * FROM users WHERE uid = '" . $_SESSION['user'] . "'");
		$user = sanitize(mysql_fetch_array($result));
	}
	else
		print "<div class='error'><span class='title'>Server Error</span></div>";
?>

		<form action="settings.php" method="post">

			<p>Username: <?php echo $user['username']; ?><br />
			<label for="email">Email:</label> <input id="email" name="email" type="text" value="<?php echo $user['email']; ?>" /></p>
			<p><label for="oldpass">Current Password:</label> <input id="oldpass" name="oldpass" type="password" value="" /><br />
			<label for="newpass">New Password:</label> <input id="newpass" name="newpass" type="password" value="" /><br />
			<label for="newpassc">New Password Confirm:</label> <input id="newpassc" name="newpassc" type="password" value="" /></p>

			<p><input type="submit" value="Update" /></p>

			<p>Images: <a href="?images=0">Off</a> <a href="?images=1">16x16</a> <a href="?images=2">32x32</a> <a href="?images=3">48x48</a></p>

		</form>
	</div>
<?php include "../footer.php"; ?>
