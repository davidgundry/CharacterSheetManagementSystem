<?php
	include "../func.inc.php";
  include "../session.php";
  unset($_SESSION['user']);
  session_destroy();
  header("location:index.php?logout=1");
?>

