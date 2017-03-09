<?php

session_start();
header ("cache-control:private");

// if (!$_GET['u']) echo "no u"; //header("Location: ../index.php");

include ('../dbconnect.php');


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT name, class, username, id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
}
else
{
	die ('You should not have gotten here.');
}

if (!$_POST['name'])
{
	die ("You didnt type a body for your message. <a href='index.php'>Go Back</a>.");
}


$insertSQL="INSERT INTO albums (creator,date,access,name,description) VALUES ('".$person['id']."','".time()."','".$_POST['access']."','".strip_tags($_POST['name'])."', '".strip_tags($_POST['description'])."')";
$insertQuery=@mysql_query($insertSQL);
if (!$insertQuery) die ('Error with insert query '.@mysql_error());
$albumid=mysql_insert_id($connection);

 header ("Location:editalbum.php?id=".$albumid);

?>


