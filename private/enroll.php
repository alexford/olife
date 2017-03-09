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

$checkSQL="SELECT * FROM enrollments WHERE person = '".$person['id']."'";
$checkQuery=@mysql_query($checkSQL);
if (!$checkQuery) die ('Error with check query! '.@mysql_query());


if ($_POST['semester']==0) $semester=1; else $semester=$_POST['semester'];

while ($check=@mysql_fetch_array($checkQuery))
{
	if ($_POST['period']==$check['period'] && $check['semester']==$semester)
		die ('You already have a class in that period. Delete the class you are already in and try again.');
}

if ($_POST['semester']!=0)
{

$saveSQL="INSERT INTO enrollments (person,class,teacher,period,semester) VALUES ('".$person['id']."','".$_POST['class']."','".$_POST['teacher']."','".$_POST['period']."','".$_POST['semester']."')";
$saveQuery=@mysql_query($saveSQL);
if (!$saveQuery) die ('Error with save query '.@mysql_error());
}
else
{
	$saveSQL="INSERT INTO enrollments (person,class,teacher,period,semester) VALUES ('".$person['id']."','".$_POST['class']."','".$_POST['teacher']."','".$_POST['period']."','1')";
$saveQuery=@mysql_query($saveSQL);
if (!$saveQuery) die ('Error with save query '.@mysql_error());

$saveSQL="INSERT INTO enrollments (person,class,teacher,period,semester) VALUES ('".$person['id']."','".$_POST['class']."','".$_POST['teacher']."','".$_POST['period']."','2')";
$saveQuery=@mysql_query($saveSQL);
if (!$saveQuery) die ('Error with save query '.@mysql_error());

}


// echo "<b>".$saveSQL."</b>";

Header ("Location:schedule.php");

?>