<?php include('dbconnect.php'); ?>
<html>
<head><title>All Announcements</title>
<link rel="stylesheet" href="olife.css" media="screen">
</head>
<body>
<center><h2 class='gold'>ANNOUNCEMENTS</h2><p>
   <?php
   		$announceSQL="SELECT announcements.approved AS approved, announcements.content AS content, announcements.heading AS heading, announcements.datetime AS datetime, people.name AS name, people.username AS username FROM announcements LEFT JOIN people ON announcements.creator=people.id ORDER BY datetime DESC";
   		$announceQuery=@mysql_query($announceSQL);
   		if (!$announceQuery) die ('Error with announcement query '.@mysql_error());
   		
   		while ($announcement=@mysql_fetch_array($announceQuery))
   		{
   				if ($announcement['approved']=='1') echo "<b>APPROVED:</b>";
   				echo "<b style='color:#222;'>".$announcement['heading']."</b><br/>";
   				echo "<b><a class='blue' href='people/".$announcement['username']."'>".$announcement['name']."</a> - ".date('g:iA n/j',($announcement['datetime']+(60*60*3)))."</b><br/>";
   				echo $announcement['content']."<br/><br/>";
   		}
    ?>
   </p>
   </center>
  </body>
  </html>