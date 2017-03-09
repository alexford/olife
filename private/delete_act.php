<?php //delete act.. MUST BE LOGGED IN
include ("../dbconnect.php");
session_start();

$deleteSQL="DELETE FROM act_enrollments WHERE activity='".$_GET['id']."' AND person='".$_SESSION['uid']."' LIMIT 1";
$deleteQuery=@mysql_query($deleteSQL);
if (!$deleteQuery) die ('Error with delete query '.@mysql_error());

header("Location: activities.php");

?>