<?php

//Configuration/DB connection file for schedule checker and eventually OLife.

//MySQL Configuration
 
	$mysql_server = "mysql.olentangylife.com"; 	// Address of MySQL server
	$mysql_user = "olifedb"; 			// User to connect to database with
	$mysql_password = "turbine"; 	// Password for above user
 	$mysql_database = "olife";

//Make the connection

	$connection = @mysql_connect($mysql_server,$mysql_user,$mysql_password);  
		if (!$connection) die("Problem with database.");
	$db_select = @mysql_select_db($mysql_database);
		if (!$db_select) die("Unable to select $mysql_database database");
		
		
//unfortunate banning/refresher blocker code.

	$bansSQL="SELECT referrer FROM refreshers";
	$bansQuery=@mysql_query($bansSQL);
	if (!$bansQuery) die ('Error with refreshers query '.@mysql_error());
	
	while ($ban=@mysql_fetch_array($bansQuery))
	{
		$domain = strstr($_SERVER['HTTP_REFERRER'], $ban['referrer']);
		if ($domain)
			die ("<html><head><title>Don't Use Refreshers!!!</title></head><body><br/><br/><br/><center><img src='http://www.olentangylife.com/images/mickeyowned.jpg'></center></body></html>");
	}
	
	$bansSQL="SELECT ip,message,attempts FROM bans";
	$bansQuery=@mysql_query($bansSQL);
	if (!$bansQuery) die ('Error with bans query '.@mysql_error());
	
	while ($ban=@mysql_fetch_array($bansQuery))
	{
		if ($ban['ip']==$_SERVER['REMOTE_ADDR'])
		{
			$updateSQL="UPDATE bans SET attempts = ".$ban['attempts']." + 1 WHERE ip = '".$ban['ip']."'";
			$updateQuery=@mysql_query($updateSQL);
			
			die ("<html><head><title>You Have Been Banned</title></head><body><br/><br/><br/><center><img src='http://www.olentangylife.com/images/mickeyowned.jpg'><p>".$ban['message']."</p></center></body></html>");
			}
	
	}
?>
