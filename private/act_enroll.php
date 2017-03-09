<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');

if (!$_SESSION['uid']) die ('How did you get here? Go back and try again.');

	$uinfoSQL="SELECT username, id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);

$checkSQL="SELECT * FROM act_enrollments WHERE person = '".$person['id']."'";
$checkQuery=@mysql_query($checkSQL);
if (!$checkQuery) die ('Error with check query! '.@mysql_query());

while ($check=@mysql_fetch_array($checkQuery))
{
	if ($_POST['activity']==$check['activity'])
		die ('You are already enrolled in that activity.');
}

$saveSQL="INSERT INTO act_enrollments (person,activity) VALUES ('".$person['id']."','".$_POST['activity']."')";
$saveQuery=@mysql_query($saveSQL);
if (!$saveQuery) die ('Error with save query '.@mysql_error());



// echo "<b>".$saveSQL."</b>";

Header ("Location:activities.php");

?>