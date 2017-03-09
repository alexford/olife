<?php

function check_enroll()
{ // this function does all 18 enrolls and returns whether or not it was sucessful.
	//return true;
	
	$person=$_SESSION['uid'];
	
	//enroll semester one
	if (!enroll($person,$_POST['s1p1class'],$_POST['s1p1teacher'],$_POST['s1p1period'],'1'))
	{
		echo 's1p1';
			deleteUser($person);
		return false;
  	return false;
	}
	if (!enroll($person,$_POST['s1p2class'],$_POST['s1p2teacher'],$_POST['s1p2period'],'1'))
	{
		echo 's1p2';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s1p3class'],$_POST['s1p3teacher'],$_POST['s1p3period'],'1'))
	{
		echo 's1p3';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s1p4class'],$_POST['s1p4teacher'],$_POST['s1p4period'],'1'))
	{
		echo 's1p4';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s1p5class'],$_POST['s1p5teacher'],$_POST['s1p5period'],'1'))
	{
		echo 's1p5';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s1p6class'],$_POST['s1p6teacher'],$_POST['s1p6period'],'1'))
	{
		echo 's1p6';
		deleteUser($person);
	return false;
	}
	if (!enroll($person,$_POST['s1p7class'],$_POST['s1p7teacher'],$_POST['s1p7period'],'1'))
	{	echo 's1p7';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s1p8class'],$_POST['s1p8teacher'],$_POST['s1p8period'],'1'))
	{	echo 's1p8';
		deleteUser($person);
	return false;
	}
	if (!enroll($person,$_POST['s1p9class'],$_POST['s1p9teacher'],$_POST['s1p9period'],'1'))
	{
			echo 's1p9';
		deleteUser($person);
		return false;
	}
	

	
	//enroll semester two
	if (!enroll($person,$_POST['s2p1class'],$_POST['s2p1teacher'],$_POST['s2p1period'],'2'))
	{
		echo 's2p1';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s2p2class'],$_POST['s2p2teacher'],$_POST['s2p2period'],'2'))
	{	
		echo 's2p2';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s2p3class'],$_POST['s2p3teacher'],$_POST['s2p3period'],'2'))
	{	echo 's2p3';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s2p4class'],$_POST['s2p4teacher'],$_POST['s2p4period'],'2'))
	{
		echo 's2p4';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s2p5class'],$_POST['s2p5teacher'],$_POST['s2p5period'],'2'))
	{
		echo 's2p5';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s2p6class'],$_POST['s2p6teacher'],$_POST['s2p6period'],'2'))
	{
		echo 's2p6';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s2p7class'],$_POST['s2p7teacher'],$_POST['s2p7period'],'2'))
	{
		echo 's2p7';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s2p8class'],$_POST['s2p8teacher'],$_POST['s2p8period'],'2'))
	{
	echo 's2p8';
		deleteUser($person);
		return false;
	}
	if (!enroll($person,$_POST['s2p9class'],$_POST['s2p9teacher'],$_POST['s2p9period'],'2'))
	{
		echo 's2p9';
		deleteUser($person);
		return false;
	}
	
	return true;
}

function enroll($person,$class,$teacher,$period,$semester)
{	//This function enrolls the specified student in the specified class.
	//echo $person."-",$class."-",$teacher."-",$period."-",$semester."-";
	if (!$person || !$class || !$teacher>=0 || !$period || !$semester) return false;
	
	$enrollSQL="INSERT INTO enrollments (person,class,teacher,period,semester) VALUES ('$person','$class','$teacher','$period','$semester')";
	$enrollQuery=@mysql_query($enrollSQL);
	if (!$enrollQuery) die (@mysql_error()); //return false;
	
	return true;
}

function deleteUser($person)
{ //for emergency removal of a person if problems were found with their schedule.

	echo 'Trying to delete user...';
	$deleteSQL="DELETE FROM people WHERE id = '$person' LIMIT 1";
	$deleteQuery=@mysql_query($deleteSQL);
	if (!$deleteQuery) die ('Error deleting user on failure. Uh oh.');
	
	$deleteEnrollSQL="DELETE FROM enrollments WHERE person ='$person'";
	$deleteEnrollQuery=@mysql_query($deleteSQL);
	if (!$deleteQuery) die ('Error deleteing user on failure. Uh Oh.!');
	
}


?>