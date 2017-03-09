<?php //update skin.. MUST BE LOGGED IN
include ("../dbconnect.php");
session_start();


$deleteSQL="UPDATE people SET skin=NULL WHERE id = '".$_SESSION['uid']."'";
$deleteQuery=@mysql_query($deleteSQL);
if (!$deleteQuery) die ('Error with update query '.@mysql_error());

header("Location: blog_skin.php");

?>