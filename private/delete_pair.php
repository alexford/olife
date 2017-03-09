<?php //delete act.. MUST BE LOGGED IN
include ("../dbconnect.php");
session_start();

$albuminfosql="SELECT creator FROM albums WHERE id='".$_GET['a']."'";
$albuminfoQuery=@mysql_query($albuminfosql);
if (!$albuminfoQuery) die ('Error with album info query '.@mysql_error());
$album=@mysql_fetch_array($albuminfoQuery);
if ($_SESSION['uid'] != $album['creator']) die('You cannot delete this pairing');

$deleteSQL="DELETE FROM album_pairs WHERE id='".$_GET['id']."'";
$deleteQuery=@mysql_query($deleteSQL);
if (!$deleteQuery) die ('Error with delete query '.@mysql_error());

header("Location: editalbum.php?id=".$_GET['a']);

?>