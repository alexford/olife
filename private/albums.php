<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');
$section="albums";


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT * FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
	
	$picSQL="SELECT filename FROM profile_pics WHERE id='".$person['pic']."'";
	$picQuery=@mysql_query($picSQL);
	if (!$picQuery) die ('Error with pic query '.@mysql_error());
	$pic=@mysql_fetch_array($picQuery);
}
else
{	
	$_SESSION['login_redirect']='private/';
	header("location: ../login.php");
	
}

if ($_GET['page']) 
$page = $_GET['page'];

//} 
else 
//{ 
$page = 1; 
//} 

// Define the number of results per page 
$max_results = 10; 

// Figure out the limit for the query based 
// on the current page number. 
$from = (($page * $max_results) - $max_results); 

	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>You :. Olentangy Life</TITLE>
    <link rel="stylesheet" media="screen" href="../olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="../images/olentangy_life.gif"></div>
  
   <div id="tagline">the best olentangy student community ever.</div>
   <div id="bar"> </div>
   <div id="login">
   
   <?php
   
   	$nummessagesQuery=@mysql_query("SELECT Count(*) as NUM FROM messages WHERE recipient='".$person['id']."' AND deleted!='1' AND unsent!='1' AND messages.read!='1'");
   	if (!$nummessagesQuery) die ('error with new messages query'.@mysql_error());
   	$messages=@mysql_fetch_array($nummessagesQuery);
      echo "<p><b>LOGGED IN AS ".$person['name']." (".$person['username'].") - <a href='../logout.php'>LOGOUT</a></b><Br/>You have ".$messages['NUM']." unread <a href='messages.php' class='blue'>message(s)</a>.</p>";
  
   ?>
   
   </div>
   <?php include ('../master_nav.php'); ?>
   
   <div id="privateleftbar" align=right>
   
  
  
	<?php include('privatenav.php'); ?>
   	
   
   </div>
   
   
   
   <div id="main">
	<h1>YOUR ALBUMS</h1>
	<p>Here is a list of your photo albums. Just click on one to edit it/add pictures, or enter a title and a description to create a new one.  Your profile and blog have links to your photo albums, and each album has its own link that you can send to people. You guessed it, no ads and unlimited (until I run out of space) photos.</p>
	<p><b>IMPORTANT:</b> There are some guidelines/limitations/rules.  There can be no nudity of any kind. If you don't want your parents to see it, don't post it here. Any nudity/blatantly explicit/pornographic pictures will be cause for immediate loss of album priveleges.</p>
	<?php
	
		$sql="SELECT id, name, access, description, num_pics, date, lastupdate FROM albums WHERE creator='".$person['id']."'";
		$query=@mysql_query($sql);
		if (!$query) die ('Error with albums query '.@mysql_error());
		
		
		if (@mysql_num_rows($query)<1)
			echo "<p><i>You have no albums.</i></p>";
		else
		{ //there are albums
			$current=0;
			echo "<table width=100% cellspacing=5 border=0><tr>";
		while ($album=@mysql_fetch_array($query))
		{
			$current++;
			$num_pics = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM album_pairs WHERE album='".$album['id']."'"),0); 
			echo "<td width=50% style='border:1px dashed #bbb; padding:10px; background:#fff;'><h2><a class='blue' href='editalbum.php?id=".$album['id']."'>".$album['name']."</a></h2><p>".$album['description']."</p><br/><p style='color:#777; margin-bottom:0px;'><b>".$num_pics." pictures</b><br/>created ".date("m/d/y",$album['date'])."<br/>last update ".date("m/d/y",$album['lastupdate']);
			if  ($album['access']==0) echo "<br/>public album";
			else if ($album['access']==1) echo "<br/>olife members only";
			else if ($album['access']==2) echo "<br/>subscriptions only";
			else if ($album['access']==3) echo "<br/>private album";
			
			echo "</p></td>";
			if ($current==2)
			{	$current=0; echo "</tr><tr>"; }
			
		}  //for each album
			echo "</table>";
 		} //end there are albums
	?>
	
	<h1>NEW ALBUM</h1>
	<p>Ready to post pictures? Enter a title, description, and access level to create an album.</p>
	<form action="newalbum.php" method="post">
	<table cellspacing=10>
	<tr><td><p style="text-align:right;"><b>Title:</b></p></td><td><input type="text" name="name" value="My Album"></td></tr>
	<tr><td valign=top><p style="text-align:right;"><b>Description:</b></p></td><td><textarea name="description" rows="10" cols="30"></textarea></td></tr>
	<tr><td valign=top><p style="text-align:right;"><b>Access:</b></p></td><td><p><input type="radio" name="access" value="0" checked>Everyone</input><br/><input type="radio" name="access" value="1">OLife Members Only</input><br/><input type="radio" name="access" value="2">People you're subscribed to<br/><input type="radio" name="access" value="3">No one (private album)</p></td></tr>
	<tr><td></td><td><button type="submit">Create album</button></td></tr>
	</table>
	</form>	
	</div>
 
  
   
  </BODY>
</HTML>