<?php
 
	include "../dbconnect.php";
	  
	$script_path = '/var/www/tak/csms/csms.sql"';
	  
	$command = 'mysql'
		. ' --host=' . $mysqlhost
		. ' --user=' . $mysqluser
		. ' --password=' . $mysqlpassword
		. ' --database=' . $mysqldatabase
		. ' --execute="SOURCE ' . $script_path
	;
	$output = shell_exec($command);

	echo $command;
	
	$pass = md5("password");
	$query = "INSERT INTO `users` (`username` ,`password` ,`email` ,`active`, `admin` ) VALUES ('admin', '$pass', '', '1', '1');";
	$result = mysql_query($query);

	header("location:index.php");
?>