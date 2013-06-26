<?php
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$starttime = $mtime;

	$NAME = "Awesome Game";
	$TAGLINE = "Character Sheet Management System";
	$admin_email = "awesomegamerefs@awesome.game";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-GB" >

  <head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title><?php echo $NAME . " | " .$PAGETITLE ?></title>
    <link rel="stylesheet" type="text/css" href="style/style.css" /> 
  </head>
  <body>
    <div id="container">
      <div id="header"></div>
	<div id="content">
	  <h1><?php
		  if ($PAGENAME == "index.php")
			  echo "Welcome to $NAME";
		  else
			  echo $PAGENAME; ?></h1>

