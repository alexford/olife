<?php

session_start();
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
	die ('Direct access not permitted/provided. How did you get here? ');
}


$postSQL="INSERT INTO picture_comments (postid,person,comment,datetime) VALUES 
('".$_POST['postid']."','".$person['id']."','".$_POST['comment']."','".time()."')";

$postQuery=@mysql_query($postSQL);
if (!$postQuery) die ('Error with post query! '.@mysql_error());


header ("Location: viewpicture.php?id=".$_POST['postid']);

?>