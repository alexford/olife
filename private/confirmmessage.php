<?php //confirm username/send post.. MUST BE LOGGED IN
include ("../dbconnect.php");
session_start();

if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT name, class, username, id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
	
}
else
{	
	$_SESSION['login_redirect']='private/';
	header("location: ../login.php");
	
}

if ($_GET['to'])
{

	$postsSQL="SELECT sender FROM messages WHERE messages.id='".$_GET['m']."'";
	
		$postsQuery=@mysql_query($postsSQL);								//do the query
		if (!$postsQuery) die ('Error with posts query '.@mysql_error());
		$message=@mysql_fetch_array($postsQuery);
		if ($message['sender']!=$person['id']) die("<p>You are not authorized to edit this message.</p></div>");
	
	$unameSQL="SELECT id FROM people WHERE username='".$_GET['to']."'";
	$unamequery=@mysql_query($unameSQL);
	if (!$unamequery) die ('error with uname query '.@mysql_error());
	$member=@mysql_fetch_array($unamequery);

	$updateSQL="UPDATE messages SET recipient = '".$member['id']."' WHERE id ='".$_GET['m']."' LIMIT 1";
	$updateQuery=@mysql_query($updateSQL);
	if (!$updateQuery) die ('Error with update query '.@mysql_error());
	
	header("Location:messages.php");
	die();
}


$exactSQL="SELECT id FROM people WHERE username='".$_POST['recipient']."'";
$exactQuery=@mysql_query($exactSQL);
if (!$exactQuery) die ('Error with search query 1'.@mysql_error());

if (@mysql_num_rows($exactQuery)==1)
{
	$recipient=@mysql_fetch_array($exactQuery);
	$insertSQL="INSERT INTO messages (sender,recipient,subject,message,datetime) VALUES 
		('".$person['id']."', '".$recipient['id']."','".strip_tags($_POST['subject'])."', '".$_POST['message']."', '".time()."')";
	//	echo $insertSQL."<br/><Br/>";
	$insertQuery=@mysql_query($insertSQL);
	if (!$insertQuery) die('Error sending message '.@mysql_error());
	
	
	header ("Location:messages.php");
	
}
else
{
	$insertSQL="INSERT INTO messages (sender,subject,message,datetime) VALUES 
		('".$person['id']."','".strip_tags($_POST['subject'])."', '".$_POST['message']."', '".time()."')";
	//	echo $insertSQL."<br/><Br/>";
	$insertQuery=@mysql_query($insertSQL);
	if (!$insertQuery) die('Error sending message '.@mysql_error());
	$messageID=@mysql_insert_id();
	
	$searchSQL="SELECT people.name AS name, people.username AS username, people.id AS id, people.sex AS sex, people.class AS class, grades.grade AS grade FROM people LEFT JOIN grades ON (people.class=grades.year) WHERE name LIKE '%".$_POST['recipient']."%' OR username LIKE '%".$_POST['recipient']."%'";
	$searchQuery=@mysql_query($searchSQL);
	if (!$searchQuery) die ('Error with search query '.@mysql_error());
}	

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>You :. Olentangy Life</TITLE>
    <link rel="stylesheet" media="screen" href="../olife.css">
  </HEAD>
  <body><Br/><br/><br/>
  <div align='center'>

  <h2>That username wasn't found</h2>
  <?php
  	if (@mysql_num_rows($searchQuery)>0)
  	{
  		echo "<p>Who do you want to send this message to?</p>";
  		while ($option=@mysql_fetch_array($searchQuery))
  		{
  			echo "<p><b><a href='confirmmessage.php?to=".$option['username']."&m=".$messageID."' class='blue'>".$option['name']." (".$option['username'].")</a></b><Br/>";
  				echo "(".$option['grade'].")";
  			
  			echo "</p>";	
  		}
  	}
  	else
  	{
  		echo "<p>I tried to find some alternatives, but came up with nothing. <a href='compose.php' class='blue'>Go back</a>.</p>";
  	}
  ?>
  

  </div>
  </body>
  
  
  </htmk>
  