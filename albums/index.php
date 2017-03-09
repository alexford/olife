<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');
if ($_SERVER['HTTP_REMOTE_ADDR'] == "156.63.253.3")
{	$message="<p><b>You are viewing this from school.  I have nothing to do with any trouble this may get you into.</b></p>"; }


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT id, name, class, username FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
	
}

if (!$_GET['u'] && !$_GET['album']) Header ("Location: about_albums.php");
if ($_GET['u'])
{
	$userinfo=one_result_query("SELECT people.id AS id, people.about AS about, people.name AS name, people.username AS username, profile_pics.filename AS pic FROM people LEFT JOIN profile_pics ON (profile_pics.id = people.pic) WHERE people.username='".$_GET['u']."'","user info");
	if (!$userinfo['name']) die('User not found');

}
else
{
	$albuminfo=one_result_query("SELECT * FROM albums WHERE id='".$_GET['album']."'","album info");
	if (!$albuminfo['creator']) die ('Album not found');
	$userinfo=one_result_query("SELECT people.id AS id, people.about AS about, people.name AS name, people.username AS username, profile_pics.filename AS pic FROM people LEFT JOIN profile_pics ON (profile_pics.id = people.pic) WHERE people.id='".$albuminfo['creator']."'","user info");
	if (!$userinfo['name']) die('User not found');
	
	
	
	//check album access!!!! added 11/27/05
	if ($albuminfo['access']==3 && $person['id']!=$albuminfo['creator'])
		die ('You do not have access to this album.');
	if ($albuminfo['access']==2)
	{
		if (!$person) die ('You do not have access to this album. Try logging in.');
		
		$subscription=one_result_query("SELECT * FROM subscriptions WHERE er='".$userinfo['id']."' AND ee='".$person['id']."'","subscription test query");
			if (!$subscription['id']) die ('You do not have access to this album.');
				
		
	}
	if ($albuminfo['access']==1)
	{
		if (!$person) die ('You do not have access to this album. Try logging in.');	
	}
}



if ($_GET['page']) 
$page = $_GET['page'];

//} 
else 
//{ 
$page = 1; 
//} 

// Define the number of results per page 
$max_results = 15; 

// Figure out the limit for the query based 
// on the current page number. 
$from = (($page * $max_results) - $max_results); 


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
   
   	
   	$nummessagesQuery=@mysql_query("SELECT id FROM messages WHERE (recipient='".$person['id']."' OR recipient='all') AND deleted!='1' AND unsent!='1' AND messages.read!='1'");
   	if (!$nummessagesQuery) die ('error with new messages query'.@mysql_error());
   	$messages=@mysql_num_rows($nummessagesQuery);
   	
      echo "<p><b>LOGGED IN AS ".$person['name']." (".$person['username'].") - <a href='../private/'>YOU</a> - <a href='../blog/?u=".$person['username']."'>YOUR BLOG</a> - <a href='../logout.php'>LOGOUT</a></b><Br/>You have ".$messages." unread <a href='/private/messages.php' class='blue'>message(s)</a>.</p>";
    }
    else
    {
    ?>
 
   
   <form method="post" action="../logincheck.php">      
    <p>
		
		<b>USER:</b>
		
		<input type="text" name="username" value="username" size=8>
		
		<b class="gold">PW:</b>
		
		<input type="password" name="password" size=8>
		<?php $_SESSION['login_redirect']="albums/?u=".$_GET['u']."&album=".$_GET['album']; ?>
		 <input class="submit" type="image" name="login" src="../images/login.gif"> <a href="register.php"class="submit"><img src="../images/register.gif" alt="register"></a>
		 
    </p>
  </form>
   
   <?php 
   
   }
   
   ?>
   
   
   </div>
 	<?php include ('../master_nav.php'); ?>
 	
   <div id="leftbar" align='right'>
   <?php
  	if ($userinfo['pic'])
  		echo "<img alt='".$userinfo['username']."' src='../blog/blogpic.php?pic=".$userinfo['pic']."' align='right' style='border:0px;'>";
   
  	?>
  	<br clear='right'>
  	<p><b><?php echo $userinfo['username']; ?></b><br/>
  	<a class='blue' href='../people/profile.php?u=<?php echo $userinfo['username']; ?>'>profile</a> - 
  	<?php if ($person) { ?><a class='blue' href='../private/compose.php?to=<?php echo $userinfo['username']; ?>'>message</a> - <?php } ?>
  	<a class='blue' href='../blog/?u=<?php echo $userinfo['username']; ?>'>blog</a><br/></p>
  	<p><?php echo $userinfo['about']; ?></p>
   
   
   <a href="http://marykay.com/llillemoen"><img src="../ad.jpg" style="border:0px;"></a>
   
   
   </div>
   
   
   
   <div id="main">
	<?php 
	
	if ($albuminfo) //we're using an album
	{
		echo "<h1>".$albuminfo['name']."</h1>";			//top album info
		echo "<h2>".$userinfo['name']."</h2>";
		echo "<center><p><b><a class='blue' href='index.php?u=".$userinfo['username']."'>back to ".$userinfo['name']."'s albums</a></b></p></center>";
		echo "<p style='margin-bottom:0px;'>".$albuminfo['description']."</p>";
		echo "<p class='soft'>last update: ".date('m/d/y',$albuminfo['lastupdate'])."</p>";
		
		
		
	
		$sql="SELECT album_pairs.id AS id, album_pics.name AS name, album_pics.description AS description, album_pics.file AS file, album_pairs.date AS date FROM album_pairs LEFT JOIN album_pics ON (album_pics.id=album_pairs.album_pic) WHERE album_pairs.album='".$_GET['album']."' ORDER BY album_pairs.date DESC LIMIT $from, $max_results";
		$query=@mysql_query($sql);
		if (!$query) die ('Error with albums query '.@mysql_error());
		
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM album_pairs WHERE album='".$album['id']."'"),0); 

		if (@mysql_num_rows($query)<1)
			echo "<p><i>There are no pictures in this album.</i></p>";
		else
		{ //there are pictures
			echo "<p><b>$total_results pictures in this album. ".mysql_num_rows($query)." shown, newest first.</b></p>";
			$current=0;
			echo "<table cellspacing=5 border=0><tr>";
		while ($picture=@mysql_fetch_array($query))
		{
			$current++;
			echo "<td valign=top width=33% style='border:1px dashed #bbb; padding:10px; background:#fff;'><h2 style='margin:0px;'><a class='blue' href='viewpicture.php?id=".$picture['id']."'>".$picture['name']."</a></h2><p style='color:#777; margin-top:0px;'>added ".date("m/d/y",$picture['date'])."</p><centeR><a href='viewpicture.php?id=".$picture['id']."'><img style='border:none;' src='../album_pics/thumbs".$picture['file']."'></a><p class='soft'>".$picture['description']."</p></center></td>";
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
	
	
	
	}  //end albums
	else //we're using a user
	{
		echo "<h1>ALBUMS BY ".$userinfo['name']."</h1>";
		
			
		
		//here we build SQL based on user conditions for access.
		$sql="SELECT id, name, access, description, num_pics, date, lastupdate FROM albums WHERE creator='".$userinfo['id']."'";
		
		if ($userinfo['id']!=$person['id'])
		{
			$sql=$sql."AND (access='0'";
		if ($person)  //more access options when we're logged in.
		{
			$sql=$sql." OR access='1'"; //include member only albums
			
			
			$subscription=one_result_query("SELECT * FROM subscriptions WHERE er='".$userinfo['id']."' AND ee='".$person['id']."'","subscription test query");
				if ($subscription['id']) {$sql=$sql." OR access='2'";} //include subscriber only albums
				
			
		}
		$sql=$sql.")";
		}
		
		
	//	die ($sql);
		
		$query=@mysql_query($sql);
		if (!$query) die ('Error with albums query '.@mysql_error());
		
		
		if (@mysql_num_rows($query)<1)
			echo "<p><i>This user has no albums, or they are hidden.</i></p>";
		else
		{ //there are albums
			$current=0;
			echo "<table width=100% cellspacing=5 border=0><tr>";
		while ($album=@mysql_fetch_array($query))
		{
			$current++;
			$num_pics = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM album_pairs WHERE album='".$album['id']."'"),0); 
			echo "<td width=50% style='border:1px dashed #bbb; padding:10px; background:#fff;'><h2><a class='blue' href='index.php?album=".$album['id']."'>".$album['name']."</a></h2><p>".$album['description']."</p><br/><p style='color:#777; margin-bottom:0px;'><b>".$num_pics." pictures</b><br/>created ".date("m/d/y",$album['date'])."<br/>last update ".date("m/d/y",$album['lastupdate']);
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

	
	
	
	} //end using a user
	
	//end album/pic list code
	?>
   </div>

<div id="rightbar">
<h2>NOTICE</h2>
<p>All pictures posted are the responsibility of the poster. To report explicit pictures, contact me at the <a class='blue' href='../about.php'>about page</a>.</p>
</div>
   
  </BODY>
</HTML>