<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');

if (!$_SESSION['uid']) die ('How did you get here?');

	$uinfoSQL="SELECT name, class, username, id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	$person=@mysql_fetch_array($uinfoQuery);
	
	$albumSQL="SELECT creator FROM albums WHERE id ='".$_POST['album']."'";
	$albumQuery=@mysql_query($albumSQL);
	if (!$albumQuery) die ('Error finding album? '.@mysql_error());
	if (mysql_num_rows($albumQuery)!=1) die ('No album or >1 albums found');
	
	$album=@mysql_fetch_array($albumQuery);
	
	if ($person['id']!=$album['creator']) die ('<p><b>You cannot edit this album.</b></p>');
	

$saveSQL="UPDATE albums
SET description = '".strip_tags($_POST['description'])."', 
access = '".$_POST['access']."' 
WHERE id ='".$_POST['album']."'";

// echo "<b>".$saveSQL."</b>";

$saveQuery=@mysql_query($saveSQL);
if (!$saveQuery) die ('Error with save query '.@mysql_error());

Header ("Location:editalbum.php?id=".$_POST['album']."&s=1");

?>ee