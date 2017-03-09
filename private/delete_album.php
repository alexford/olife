<?php //delete post.. MUST BE LOGGED IN
include ("../dbconnect.php");
session_start();

$postsSQL="SELECT creator FROM albums WHERE id = '".$_GET['id']."' LIMIT 1";
$postsQuery=@mysql_query($postsSQL);
if (!$postsQuery) die ('Error with posts query! '.@mysql_error());
$post=@mysql_fetch_array($postsQuery);

if ($post['creator']!=$_SESSION['uid']) die ('<h1>YOU CAN NOT DELETE THIS ALBUM.</h1>');

$deleteSQL="DELETE FROM albums WHERE id = '".$_GET['id']."'";
$deleteQuery=@mysql_query($deleteSQL);
if (!$deleteQuery) die ('Error with delete query '.@mysql_error());

$deleteSQL="DELETE FROM album_pairs WHERE album = '".$_GET['id']."'";
$deleteQuery=@mysql_query($deleteSQL);
if (!$deleteQuery) die ('Error with delete query '.@mysql_error());

header("Location: albums.php");

?>