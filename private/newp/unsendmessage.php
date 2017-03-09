<?php //delete post.. MUST BE LOGGED IN
include ("../dbconnect.php");
session_start();

$postsSQL="SELECT * FROM messages WHERE id = '".$_GET['id']."' LIMIT 1";
$postsQuery=@mysql_query($postsSQL);
if (!$postsQuery) die ('Error with posts query! '.@mysql_error());
$post=@mysql_fetch_array($postsQuery);

if ($post['sender']!=$_SESSION['uid']) die ('<h1>YOU CANNOT UNSEND THIS MESSAGE.</h1>');

$deleteSQL="UPDATE messages SET unsent=1 WHERE id = '".$_GET['id']."'";
$deleteQuery=@mysql_query($deleteSQL);
if (!$deleteQuery) die ('Error with delete query '.@mysql_error());

header("Location: messages.php");

?>