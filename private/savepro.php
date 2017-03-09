<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');

if (!$_SESSION['uid']) die ('How did you get here?');

	$uinfoSQL="SELECT username FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);

	if ($_FILES['image']['type'])
	{
	
	if ($_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/pjpeg")
	{
		if ($_FILES['image']['size'] > 100000)
			die ('Your image is too big. Jerk. If you really wanna use that image, ask me and I might help you.');
		  $newname=$_FILES['image']['tmp_name'];
		//  echo $newname."<br/>";
		  
		  $newname=$newname.".jpg";
		 	
	 //  	echo $newname."<br/>";
 //		  echo  $_FILES['image']['tmp_name'];
		  copy ($_FILES['image']['tmp_name'], "../pics/".$newname) or die ("Could not copy");
		  $picSQL="INSERT INTO profile_pics (filename,filesize,filetype,user) VALUES 
		  ('".$newname."', 
		  '".$_FILES['image']['size']."', 
		  '".$_FILES['image']['type']."',
		  '".$person['username']."')";
		  $picQuery=@mysql_query($picSQL);
		  if (!$picQuery) die ('Error with pic query '.@mysql_error());
		  $picid = @mysql_insert_id();
		  
		  $saveSQL="UPDATE people 
SET profile = '".$_POST['profile']."', 
aim = '".strip_tags($_POST['aim'])."', 
name = '".strip_tags($_POST['name'])."',
about = '".$_POST['about']."',
journal = '".$_POST['journal']."',
pic = '".$picid."'
WHERE id ='".$_SESSION['uid']."'";
		  
	}
	else die ('Your file is not a jpeg. I think it is a '.$_FILES['image']['type'].' Please use your browsers back button and select a jpeg.');
   }
	else
	{
		  $saveSQL="UPDATE people 
SET profile = '".$_POST['profile']."', 
aim = '".strip_tags($_POST['aim'])."', 
name = '".strip_tags($_POST['name'])."',
about = '".$_POST['about']."',
journal = '".$_POST['journal']."'
WHERE id ='".$_SESSION['uid']."'";
	
	}


// echo "<b>".$saveSQL."</b>";

$saveQuery=@mysql_query($saveSQL);


if (!$saveQuery) die ('Error with save query '.@mysql_error());

Header ("Location:index.php");

?>