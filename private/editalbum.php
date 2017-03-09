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
	
//	$picSQL="SELECT filename FROM profile_pics WHERE id='".$person['pic']."'";
//	$picQuery=@mysql_query($picSQL);
//	if (!$picQuery) die ('Error with pic query '.@mysql_error());
//	$pic=@mysql_fetch_array($picQuery);
}
else
{	
	$_SESSION['login_redirect']='private/';
	header("location: ../login.php");
	
}

if (!$_GET['id']) {	header("location: albums.php"); }
else
{
	$albumSQL="SELECT * FROM albums WHERE id ='".$_GET['id']."'";
	$albumQuery=@mysql_query($albumSQL);
	if (!$albumQuery) die ('Error with album query '.@mysql_error());
	if (@mysql_num_rows($albumQuery)!=1) die ('<p><b>Album not found or multiple albums found.</b></p>');
	
	$album=@mysql_fetch_array($albumQuery);
	
	if ($album['creator']!=$person['id']) //not your album
		die ('<p><b>You cannot edit this album.</b></p>');
		
}



if ($_GET['page']) 
$page = $_GET['page'];

//} 
else 
//{ 
$page = 1; 
//} 

// Define the number of results per page 
$max_results = 12; 

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
   <h1>ALBUM PICTURES</h1>
  
	<?php
	
		$sql="SELECT album_pairs.id AS id, album_pics.name AS name, album_pics.file AS file, album_pairs.date AS date FROM album_pairs LEFT JOIN album_pics ON (album_pics.id=album_pairs.album_pic) WHERE album_pairs.album='".$_GET['id']."' ORDER BY album_pairs.date DESC LIMIT $from, $max_results";
		$query=@mysql_query($sql);
		if (!$query) die ('Error with albums query '.@mysql_error());
		
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM album_pairs WHERE album='".$album['id']."'"),0); 

		if (@mysql_num_rows($query)<1)
			echo "<p><i>There are no pictures in this album. Add some with the form below.</i></p>";
		else
		{ //there are pictures
			echo "<p><b>$total_results pictures in this album. ".mysql_num_rows($query)." shown</b></p>";
			$current=0;
			echo "<table cellspacing=5 border=0><tr>";
		while ($picture=@mysql_fetch_array($query))
		{
			$current++;
			echo "<td valign=top width=33% style='border:1px dashed #bbb; padding:10px; background:#fff;'><h2 style='margin:0px;'><a class='blue' href='viewpicture.php?id=".$picture['id']."'>".$picture['name']."</a></h2><p style='color:#777; margin-top:0px;'>added ".date("m/d/y",$picture['date'])."</p><centeR><a href='viewpicture.php?id=".$picture['id']."'><img style='border:none;' src='../album_pics/thumbs".$picture['file']."'></a></center></td>";
			if ($current==3)
			{	$current=0; echo "</tr><tr>"; }
			
		}  //for each album
			echo "</table>";
			
// Figure out the total number of pages. Always round up using ceil() 
$total_pages = ceil($total_results / $max_results); 

// Build Page Number Hyperlinks 
echo "<center>"; 
echo "<div class='pagelinks'>";
// Build Previous Link 
if($page > 1){ 
$prev = ($page - 1); 
echo "<a href=\"".$_SERVER['PHP_SELF']."?id=".$album['id']."&page=$prev\">Previous 12</a> "; 
} 



// Build Next Link 
if($page < $total_pages){ 
$next = ($page + 1); 
echo "<a href=\"".$_SERVER['PHP_SELF']."?id=".$album['id']."&page=$next\">Next 12</a>"; 
} 

echo "</div>";
echo "</center>"; 
			
			
 		} //end there are albums
	?>
	
	
	
	<h1>ADD PICTURE</h1>
	<p>To add a picture to your album, use this form.</p>
	<form method="post" action="addpicture.php" enctype="multipart/form-data">
	<input type="hidden" name="album" value="<?php echo $album['id']; ?>">
	<table cellspacing=10>
	<tr><td><p style="text-align:right;"><b>Name:</b></p></td><td><input type="text" name="name" value="My Picture"></td></tr>
	<tr><td valign=top><p style="text-align:right;"><b>Description:</b></p></td><td><textarea name="description" rows="10" cols="30"></textarea></td></tr>
	<tr><td valign=top><p style="text-align:right;"><b>File (jpg only):</b></p></td><td><input name="image" type="file" size="15"></td></tr>
	<tr><td></td><td><button type="submit">Add Picture</button></td></tr>
	</table>
	</form>	
	</div>
 
 <div id="rightbar">
 	<h1>ALBUM INFO</h1>
  <h2><?php echo $album['name']; ?></h2><br/>
  <form action="mod_album.php" method="post">

	<textarea name="description" rows="10" cols="20"><?php echo $album['description']; ?></textarea>
	<p><b>Access:</b></p>
	<table><tr><td><p><input type="radio" name="access" value="0" <?php if ($album['access']==0) echo 'checked'; ?>>everyone</input><br/><input type="radio" name="access" value="1" <?php if ($album['access']==1) echo 'checked'; ?>>members</input><br/><input type="radio" name="access" value="2" <?php if ($album['access']==2) echo 'checked'; ?>>subscriptions<br/><input type="radio" name="access" value="3" <?php if ($album['access']==3) echo 'checked'; ?>>private</p></td></tr>
	<tr><td><button type="submit">Save</button></td></tr>
	</table>
	<?php if ($_GET['s']==1) echo "<p><i>changes saved</i></p>"; ?>
	<input type="hidden" name="album" value="<?php echo $album['id']; ?>">
</form>	<br/><br/><br/>
<center><p><a class='blue' href='delete_album.php?id=<?php echo $_GET['id']; ?>'>delete album...</a><br/>make sure you really want to first... you're really screwed if you click this on accident</p></center>
 </div>
  
   
  </BODY>
</HTML>