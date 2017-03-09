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
	
if (!$_GET['u']) die ('No user selected to subscribe to');
$subsribeeSQL="SELECT id FROM people WHERE username='".$_GET['u']."'";
$subscribeeQuery=@mysql_query($subsribeeSQL);
if (!$subscribeeQuery) die ('Couldnt find the person you want to subscribe to..? '.@mysql_error());
$subscribee=@mysql_fetch_array($subscribeeQuery);
$subscribetoid=$subscribee['id'];
$subscribeforid=$_SESSION['uid'];

$subscribeSQL="DELETE FROM subscriptions WHERE ee = '".$subscribetoid."' AND er ='".$subscribeforid."' LIMIT 1";
$subscribeQuery=@mysql_query($subscribeSQL);
if (!$subscribeQuery) die ('Error with unsubscribe query '.@mysql_error());


Header ("Location: ".$_SESSION['subscribe_redirect']);

?>