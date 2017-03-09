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

if (!$_POST['body'])
{
	die ("You didnt type a body for your message. <a href='index.php'>Go Back</a>.");
}


$insertSQL="INSERT INTO posts (person,datetime,body,heading,deleted) VALUES ('".$person['id']."','".time()."','".$_POST['body']."','".strip_tags($_POST['heading'])."', '0')";
$insertQuery=@mysql_query($insertSQL);
if (!$insertQuery) die ('Error with insert query '.@mysql_error());

$updateSQL="UPDATE people SET lastupdate = '".time()."' WHERE id ='".$person['id']."'";
$updateQuery=@mysql_query($updateSQL);
if (!$updateQuery) die ('Error with update query '.@mysql_error());

if ($_POST['redirect']=="blog") header ("Location:../blog/?u=".$person['username']);
if ($_POST['redirect']=="private") header ("Location:posts.php");

?>


