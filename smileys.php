<?php include ('dbconnect.php'); ?>


<html>
<head><title>OLIFE SMILEYS TEST</title></head>
<link rel="stylesheet" media="screen" href="olife.css">
</head>
<body>

<table cellspacing=10>
<tr><td><p><b>NAME</b></p></td><td><p><b>CODE</b></p></td><td><p><b>SMILEY</b></p></td></tr>
<?php
	
	$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
	$smileysQuery=@mysql_query($smileysSQL);
	if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
	
	
	while ($smiley=@mysql_fetch_array($smileysQuery))
	{
	
		echo "<tr><td><p>".$smiley['name']."</b></p></td>";
		echo "<td><p>".$smiley['ocode']."</p></td>";
		echo "<td><img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'></td></tr>";
		
	}
	


?>
</table>

<?php
	$theString="Here i can add :smile: eys. Such as :redface: that one.";
	echo "<h2>Test</h2>";
	echo "<p>".$theString."</p>";
	$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
	$smileysQuery=@mysql_query($smileysSQL);
	if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
	
	
	while ($smiley=@mysql_fetch_array($smileysQuery))
	{
		$theString=str_replace($smiley['ocode'],"<img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'>",$theString);
		
	}
	
	echo "<p>".$theString."</p>";
	
	
?>
</body>
</html>