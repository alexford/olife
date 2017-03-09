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

$postsSQL="SELECT * FROM posts WHERE id = '".$_POST['postid']."' LIMIT 1";
$postsQuery=@mysql_query($postsSQL);
if (!$postsQuery) die ('Error with posts query! '.@mysql_error());
$post=@mysql_fetch_array($postsQuery);

if ($post['person']!=$_SESSION['uid']) die ('<h1>YOU CAN NOT EDIT THIS POST.</h1>');


if (!$_POST['body'])
{
	die ("You didnt type a body for your message. <a href='edit.php?id=".$_POST['postid'].">Go Back</a>.");
}


$insertSQL="UPDATE posts SET picture = '".$_POST['picture']."', body = '".$_POST['body']."', heading ='".$_POST['heading']."' WHERE id = '".$_POST['postid']."'"; 
$insertQuery=@mysql_query($insertSQL);
if (!$insertQuery) die ('Error with insert query '.@mysql_error());

$updateSQL="UPDATE people SET lastupdate = '".time()."' WHERE id ='".$person['id']."'";
$updateQuery=@mysql_query($updateSQL);
if (!$updateQuery) die ('Error with update query '.@mysql_error());

if ($_POST['redirect']=="blog") header ("Location:../blog/?u=".$person['username']);
if ($_POST['redirect']=="private") header ("Location:index.php");

?>


