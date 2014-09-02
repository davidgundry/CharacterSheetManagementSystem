<?php
  $PAGENAME = "Register";
  $PAGETITLE= "Register an account";
  include "../func.inc.php";
  include "../header.php";

  /* Need to connect to db for sanitization function to work.
     Then sanitize username and email or die with an error. */
  include "../dbconnect.php";
  if (function_exists('sanitize'))
  {
      if ((isset($_POST['username'])) and (isset($_POST['email'])) and (isset($_POST['password'])) and isset($_POST['password-c']))
      {
	  $user = sanitize($_POST['username']);
	  $email = sanitize($_POST['email']);
	  $pass = $_POST['password'];
	  $passcon = $_POST['password-c'];
	  
	  if ($user == '')
	  {
		print "<div class='error'><span class='title'>No username</span>No username was suppplied.</div>";
	  }
	  else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	  {
		print "<div class='error'><span class='title'>Invalid Email</span>The email address supplied is invalid.</div>";
	  }
	  else if ($pass == $passcon)	
	  {
	  	  $pass = md5($pass);
		  $query = "INSERT INTO `users` (`username` ,`password` ,`email` ,`active` ) VALUES ('$user', '$pass', '$email', '0');";
		  $result = mysql_query($query);
		  print "<div class='notification'><span class='title'>Submitted for approval</span>The new user account has been submited for adminstrator approval.</p><p>Go to <a href='index.php'>Login</a> page.</div>";
	  }
	  else
	  {
		print "<p>The two passwords did not match.</p.>";
	  }
      }
  }
  else
  {
		print "<p>Server Error. Uh... Something went wrong. If it happens again, shout at us until we fix it.</p>";
		die();
  }
?>

<div class='textgroup'>
	<h2>Register</h2>
		<p>This will register a user account on the <?php $NAME ?> <abbr title="Character Sheet Management System">CSMS</abbr>. Before you can use it, it must be activated by a administrator.</p>
		<form action='register.php' method='post'>
			<p>
				Username: <input type='text' name='username' /><br />
				Password: <input type='password' name='password' /><br />
				Confirm Password: <input type='password' name='password-c' /><br />
				Email: <input type='text' name='email' /><br />
				<input type='submit' value='Register' />
			</p>
		</form>
		
		<p>Return to <a href="index.php">Login</a> page.</p>
</div>

<?php

  include "../footer.php";
?> 
