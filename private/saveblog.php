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
	

if (!$_POST['allow_comments']) $comments=1;

$saveSQL="UPDATE people 
SET headline = '".strip_tags($_POST['headline'])."', 
tagline = '".strip_tags($_POST['tagline'])."', 
theme ='".$_POST['theme']."',
allow_comments = '".$comments."' 
WHERE id ='".$_SESSION['uid']."'";

// echo "<b>".$saveSQL."</b>";

$saveQuery=@mysql_query($saveSQL);
if (!$saveQuery) die ('Error with save query '.@mysql_error());

Header ("Location:blog.php");

?>