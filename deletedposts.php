<?php include ("dbconnect.php"); ?>

<html>
<head>
<title>All Deleted Posts Ever</title>
<link rel="stylesheet" media="screen" href="olife.css">
</head>

<body>
<center><br/><br/><h1>All Profile Pics Ever</h1></center>

<?php
	
	$numperrow=5;
	$current=0;
	$picSQL="SELECT posts.body AS body, people.name AS name, posts.heading AS heading, posts.datetime AS datetime FROM posts LEFT JOIN people on (posts.person=people.id) WHERE deleted='1' ORDER BY datetime DESC";
	$picQuery=@mysql_query($picSQL);
	if (!$picQuery) die ('error with picture query'.@mysql_error());

while($post=@mysql_fetch_array($picQuery))
		{
			echo "<div class='box'>";
			echo "<h2 class='gold'>".$post['heading']."</h2><font class='date'>".date('l n/j g:iA',($post['datetime']+(60*60*3)))."</font>";
			echo "<p><b>".$post['name']."</b><br/>";
			$theString = str_replace("\n", "<br />", $post['body']."</p>");	
			$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
	$smileysQuery=@mysql_query($smileysSQL);
	if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
	
	
	while ($smiley=@mysql_fetch_array($smileysQuery))
	{
		$theString=str_replace($smiley['ocode'],"<img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'>",$theString);
		
	}
	
	echo $theString;
	
	echo "</div>";
}

?>


</body>
</html>