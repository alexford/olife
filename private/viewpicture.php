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
}
else
{	
	$_SESSION['login_redirect']='private/';
	header("location: ../login.php");
	
}


if (!$_GET['id']) {	header("location: albums.php"); }
else
{
	$albumSQL="SELECT albums.id AS album_id, album_pics.file AS file, album_pics.name AS name, album_pics.description AS description, album_pics.id AS id, albums.creator AS album_person FROM album_pairs LEFT JOIN albums ON (albums.id=album_pairs.album) LEFT JOIN album_pics ON (album_pics.id=album_pairs.album_pic) WHERE album_pairs.id ='".$_GET['id']."'";
	$albumQuery=@mysql_query($albumSQL);
	if (!$albumQuery) die ('Error with album query '.@mysql_error());
	if (@mysql_num_rows($albumQuery)!=1) die ('<p><b>Album_pair not found or multiple album pairs found</b></p>');
	
	$picture=@mysql_fetch_array($albumQuery);
	
	if ($picture['album_person']!=$person['id']) //not your album
		die ('<p><b>You cannot edit this picture (in this album).</b></p>');
		
}

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
   
   
   
   <div id="mainnoright">
	<h1>VIEW PICTURE</h1>
	<p><a class='blue' href='editalbum.php?id=<?php echo $picture['album_id']; ?>'>back to album</a></p>
	

		<center>
		<h2><?php echo $picture['name']; ?></h2>
		<p><a class='blue'  href='delete_pair.php?id=<?php echo $_GET['id']."&a=".$picture['album_id']; ?>'>remove from album</a></p>
		<p><?php echo $picture['description']; ?><br/></p>
		<img src="../album_pics<?php echo $picture['file']; ?>" alt="<?php echo $picture['name']; ?>" style="margin-right:15px" align="center"><br/><br/>
		<p><b>
		<?php
			$comments = one_result_query("SELECT count(*) AS num FROM picture_comments WHERE postid='".$_GET['id']."'","comment query");
			echo "<a href='../albums/viewpicture.php?id=".$_GET['id']."' class='blue'>".$comments['num']." comment(s)</a>";
			
		?>
		</b></p>
		</center>
	
	</div>
 
  
   
  </BODY>
</HTML>