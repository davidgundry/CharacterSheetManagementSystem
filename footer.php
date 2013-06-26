	<div id="footer">
	      <?php
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$totaltime = ($endtime - $starttime);
		echo "This page was created in ".$totaltime." seconds | " . $TAGLINE;
	      ?>
	</div>      
    </div>  
  </body>
</html> 
