<?php
    /* Update these details with the details for your database */
    $mysqlhost = "localhost";
    $mysqluser = "root";
    $mysqlpassword = "password";
    $mysqldatabase = "csms";
    
    mysql_connect($mysqlhost,$mysqluser,$mysqlpassword) or (print("<div class='error'><span class='title'>Database Error</span>Could not connect to database.</div>"));
    mysql_select_db($mysqldatabase);
?>
