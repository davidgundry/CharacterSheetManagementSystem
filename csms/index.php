<?php
  $PAGENAME = "index.php";
  $PAGETITLE = "Login to see your Character Sheet";

  include "../func.inc.php"; 

	/* If the user failed to log in, we don't want to try again */
	if (!isset($_GET['error']))
	{
		if ((isset($_POST['username'])) and (isset($_POST['password'])))
		{
		  /* Need to connect to db for sanitization function to work.
			 Then sanitize username or show an error. */
			include "../dbconnect.php";
			if (function_exists('sanitize'))
			{
				$user = sanitize($_POST['username']);
				$pass = md5($_POST['password']);
				
				/* Try and find the username and password combo in the database. The user also needs to have been activated. */
				$query = "SELECT uid FROM users WHERE username = '$user' AND password = '$pass' AND active = '1' LIMIT 1";
				$result = mysql_query($query) or die();
				if(mysql_num_rows($result)==1)
				{
					/* If the user has successfully logged in. */
					session_start();
					while($row = mysql_fetch_array($result))
						$_SESSION['user'] = $row['uid'];
					header("location:player.php");

				} else
				{
					/* If the user has failed to log in, refresh this page with an error. */
					header("location:index.php?error=1");
				}
			}
			else
			{
				print "<div class='error'><span class='title'>Server Error</span>Uh... Something went wrong. If it happens again, shout at us until we fix it.</div>";
			}
		}
	}


	/* If the user failed to log in, tell them about it. */
	if (isset($_GET['error']))
		print("<div class='error'><span class='title'>Login Failed</span>There is no active user who matches the username and password supplied. You may get this error if you account has not yet been enabled by an administrator, or has been deactivated.</div>");
	if (isset($_GET['logout']))
		print("<div class='notification'><span class='title'>Logged Out</span>You have been logged out of the Character Sheet Management System. Log in again to see your characters.</div>");
		
		
  include "../header.php"; 
?>

	<div class='floatingbox'>
		<h2>Login</h2>

			<form action='index.php' method='post'>
				<p>
					Username: <input type='text' name='username' /><br />
					Password: <input type='password' name='password' /><br />
					<input type='submit' value='Login' />
				</p>
			</form>
			<p><a href="register.php" title='Sign up to be able to see your characters'>Register</a></p>
	</div>


	<h2>News</h2>
		<?php 
			include "../dbconnect.php";
			$query="SELECT * FROM news ORDER BY uid DESC";
			$result = mysql_query($query);
			while ($row = mysql_fetch_array($result))
				echo "<div class='newsitem'><h3>".$row['title']."</h3><p class='time'>".$row['time'] . "</p><p class='news'>".nl2br($row['news'])."</p></div>";
		?>

</div>
<?php include "../footer.php"; ?>
