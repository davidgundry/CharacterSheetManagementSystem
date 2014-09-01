<?php 
	$PAGENAME = "News Manager";
	$PAGETITLE= "Make a news post";
	include "../func.inc.php";
	include "../session.php";

	if ($user['admin'] != 1)
	{
		header("location:logout.php");
		die();
	}

	if (isset($_GET['a']))
	{
		if ($_GET['a'] == "del")
		{
			$query = "DELETE FROM news WHERE uid='".intval($_GET['uid'])."';";
			$result = mysql_query($query);
		}

		if ($_GET['a'] == "add")
		{
			$_POST = sanitize($_POST);
			$query = "INSERT INTO news SET title='".$_POST['title']."', news='".$_POST['content']."';";
			$result = mysql_query($query);
		}
	}
	
	include "../header.php";
	include "../menu.php";

?>

	<h2>Add News</h2>
		<form action="news.php?a=add" method="post">
			<p><?php echo $IMGNEWSADD; ?>Title: <input type="text" name="title" /><br />
				Content:<br />
				<textarea name="content" rows="10" cols="20"></textarea><br />
				<input type="submit" value="Submit" />
			</p>
		</form>
		<hr />
		<?php
			$query="SELECT * FROM news ORDER BY uid DESC";
			$result=mysql_query($query);
			while($row = mysql_fetch_array($result))
			{
				echo "<div class='newsitem'><h3>".$row['title']."</h3><p class='time'>".$row['time'] . "</p><p class='news'>".nl2br($row['news'])."</p>";
				echo "<p><a href='?a=del&amp;uid=".$row['uid']."' title='Delete this news post'>".$IMGNEWSDELETE." Delete</a></p></div>";
			}
		?>
</div>

<?php
	include "../footer.php";
?>
