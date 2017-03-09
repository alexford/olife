<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');
if ($_SERVER['HTTP_REMOTE_ADDR'] == "156.63.253.3")
{	$message="<p><b>You are viewing this from school.  I have nothing to do with any trouble this may get you into.</b></p>"; }


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT name, class, username FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
	
}
	
	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>Olentangy Life :. Albums</TITLE>
    <link rel="stylesheet" media="screen" href="../olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="../images/olentangy_life.gif" ALT='Olentangy Life'></div>
  
   <div id="tagline">the best olentangy student community ever.</div>
   <div id="bar"> </div>
   <div id="login">
   
   <?php
   
   if ($person)
   {
      echo "<p><b>LOGGED IN AS ".$person['name']." (".$person['username'].") - <a href='private/'>YOU</a> - <a href='blog/?u=".$person['username']."'>YOUR BLOG</a> - <a href='logout.php'>LOGOUT</a></b></p>";
    }
    else
    {
    ?>
 
   
   <form method="post" action="logincheck.php">      
    <p>
		
		<b>USER:</b>
		
		<input type="text" name="username" value="username" size=8>
		
		<b class="gold">PW:</b>
		
		<input type="password" name="password" size=8>
		<?php $_SESSION['login_redirect']="index.php"; ?>
		 <input class="submit" type="image" name="login" src="../images/login.gif"> <a href="../register.php"class="submit"><img src="../images/register.gif" alt="register"></a>
		 
    </p>
  </form>
   
   <?php 
   
   }
   
   ?>
   
   
   </div>
 	<?php include ('../master_nav.php'); ?>
 	
 	
   <div id="leftbar">
   	<center>
	<h2>JUST ADDED</h2>
	<p>click the photos for full size</p>
	<?php
		$sql="SELECT DISTINCT album_pics.file AS file, album_pairs.id AS id, album_pairs.album_pic AS pic, album_pairs.album AS album, albums.access AS access FROM album_pairs 
		LEFT JOIN albums ON (albums.id = album_pairs.album) 
		LEFT JOIN album_pics ON (album_pics.id = album_pairs.album_pic)
		WHERE albums.access = '0' 
		ORDER BY album_pairs.date DESC LIMIT 5";
		$query = @mysql_query($sql);
		if (!$query) die ('Error with random pics query '.@mysql_error());
		
		while ($picture = @mysql_fetch_array($query))
		{
			echo "<a href='viewpicture.php?id=".$picture['id']."'><img src='../album_pics/thumbs".$picture['file']."' style='border:none;'></a><br/><br/>";
			
			
		}
		
	
	?>
  
    <a href="http://marykay.com/llillemoen"><img src="../ad.jpg" style="border:0px;"></a><br/>
 
   </center>
   </div>
   
   
   
   <div id="mainnoright">
	<h1>ALBUMS</h1>
	<p>Welcome to Albums. Albums is free picture hosting for Olentangy Students.  Think of it as a simpler, easier, faster, unlimited, and ad-free version of a certain 
	picture hosting site that rhymes with Bedshots. In this initial version, you can create as many albums with as many pictures as you'd like.  You can view others' albums
	through links on their profile and blog pages.  When you create a new post, you can choose to attach a picture from one of your public albums, and it will show up
	on your blog with your entry.  Members can leave comments on your pictures, and you can choose the access level for every album.</p>
	<p>To get started, go to <a href='../private/albums.php' class='blue'>olentangylife.com/private/albums.php</a>.</p>
	<p>There will be more to this page soon, like recent photos and photo search.</p>
	
	<center>
	<h2>ZANY PHOTO GRAB-BAG</h2>
	<p>click the photos for full size</p>
	<?php
		$sql="SELECT DISTINCT album_pics.file AS file, album_pairs.id AS id, album_pairs.album_pic AS pic, album_pairs.album AS album, albums.access AS access FROM album_pairs 
		LEFT JOIN albums ON (albums.id = album_pairs.album) 
		LEFT JOIN album_pics ON (album_pics.id = album_pairs.album_pic)
		WHERE albums.access = '0' 
		ORDER BY RAND() LIMIT 12";
		$query = @mysql_query($sql);
		if (!$query) die ('Error with random pics query '.@mysql_error());
		
		echo "<table class='box' cellspacing=10><tr>";
		$on=1;
		$max=3;
		while ($picture = @mysql_fetch_array($query))
		{
			echo "<td valign=center align=center><a href='viewpicture.php?id=".$picture['id']."'><img src='../album_pics/thumbs".$picture['file']."' style='border:none;'></a></td>";
			if  ($on == 3)
			{	echo "</tr><tr>";  $on=0; }
			$on++;
			
		}
		echo "</tr></table></center>";
	?>
	<br/><br/><br/>
	<p><i>All pictures are the responsibility of the poster. To report explicit pictures, contact me at the <a class='blue' href='../about.php'>about</a> page.</i></p>




</div>


   
  </BODY>
</HTML>