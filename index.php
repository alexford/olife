<?php

session_start();
header("cache-control:private");

include ('dbconnect.php');
if ($_SERVER['REMOTE_ADDR'] == "156.63.253.3")
{	$message="<p><b>You are viewing this from school.  I have nothing to do with any trouble this may get you into.</b></p>"; }


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT name, class, username, id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
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
    <TITLE>Olentangy Life :. Home</TITLE>
    <link rel="stylesheet" media="screen" href="olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="images/olentangy_life.gif" ALT='Olentangy Life'></div>
  
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
 
   
   <form method="post" action="logincheck.php">      
    <p>
		
		<b>USER:</b>
		
		<input type="text" name="username" value="username" size=8>
		
		<b class="gold">PW:</b>
		
		<input type="password" name="password" size=8>
		<?php $_SESSION['login_redirect']="index.php"; ?>
		 <input class="submit" type="image" name="login" src="images/login.gif"> <a href="register.php"class="submit"><img src="images/register.gif" alt="register"></a>
		 
    </p>
  </form>
   
   <?php 
   
   }
   
   ?>
   
   
   </div>
   <?php include("master_nav.php"); ?>
   
   <div id="leftbar">
   
  
   <center><h2 class="gold">JUST UPDATED</h2>
   <p>
	<?php
	
	$updateSQL="SELECT username, lastupdate FROM people WHERE lastupdate>0 ORDER BY lastupdate DESC LIMIT 5";
	$updateQuery=@mysql_query($updateSQL);
	if (!$updateQuery) die ('Error with new update query...'.@mysql_error());
	
	while ($update=@mysql_fetch_array($updateQuery))
	{
		echo "<b><a href='blog/?u=".$update['username']."'>".$update['username']."</a></b> ".date('g:iA n/j',($update['lastupdate']+(60*60*3)))."<br/>";
	}
	
	?>

   </p></center>
   
   <center><h2 class="gold">NEW USERS</h2>
   <p>
	<?php 
	$usersSQL="SELECT username, sex, class FROM olife.people ORDER BY id DESC LIMIT 5";
	$usersQuery=@mysql_query($usersSQL);
	if (!$usersQuery) die ('Error with new user query...'.@mysql_error());
	
	while ($user=@mysql_fetch_array($usersQuery))
	{
		
		echo "<b><a href='people/profile.php?u=".$user['username']."'>".$user['username']." (0".$user['class'].")</a></b><br/>";
	}
	
//	echo "<p>".$user['num']." users</p>";
	
	$usersSQL="SELECT COUNT(*) AS num FROM olife.people ORDER BY id";
	$usersQuery=@mysql_query($usersSQL);
	if (!$usersQuery) die ('Error with new user query...'.@mysql_error());
	$num=@mysql_fetch_array($usersQuery);
	echo "<p><b>".$num['num']."</b> users<br/>";
	
	$usersSQL="SELECT COUNT(*) AS num FROM olife.comments ORDER BY id";
	$usersQuery=@mysql_query($usersSQL);
	if (!$usersQuery) die ('Error with new user query...'.@mysql_error());
	$num=@mysql_fetch_array($usersQuery);
	echo "<b>".$num['num']."</b> comments<br/>";
	
	$usersSQL="SELECT COUNT(*) AS num FROM olife.posts WHERE deleted !='1' ORDER BY id";
	$usersQuery=@mysql_query($usersSQL);
	if (!$usersQuery) die ('Error with new user query...'.@mysql_error());
	$num=@mysql_fetch_array($usersQuery);
	echo "<b>".$num['num']."</b> posts<Br/>";
	
		$usersSQL="SELECT COUNT(*) AS num FROM olife.messages WHERE deleted !='1' AND recipient!=0 ORDER BY id";
	$usersQuery=@mysql_query($usersSQL);
	if (!$usersQuery) die ('Error with new user query...'.@mysql_error());
	$num=@mysql_fetch_array($usersQuery);
	echo "<b>".$num['num']."</b> messages<br/>";
	
		
		$usersSQL="SELECT COUNT(*) AS num FROM olife.album_pics";
	$usersQuery=@mysql_query($usersSQL);
	if (!$usersQuery) die ('Error with new user query...'.@mysql_error());
	$num=@mysql_fetch_array($usersQuery);
	echo "<b>".$num['num']."</b> album pictures</p>";
	
	?>
   </p></center>
   
   <?php
   			$randomSQL="SELECT people.username, people.name, people.sex, people.about, profile_pics.filename AS pic FROM people LEFT JOIN profile_pics on (people.pic = profile_pics.id) WHERE people.about !='' AND people.lastupdate !='' ORDER BY rand() LIMIT 1";
   			$randomQuery=@mysql_query($randomSQL);
   			if (!$randomQuery) die ('Error with random query '.@mysql_error());
   			
   			
   			$random=@mysql_fetch_array($randomQuery);
   			echo "<center>";
   			echo "<h2 class='gold'>RANDOM USER</h2><br/>";
   			if ($random['pic']) echo "<a href='people/profile.php?u=".$random['username']."'><img style='border:none; margin-bottom:10px;' src='people/userpic.php?pic=".$random['pic']."'></a><Br clear='left' />";
   		
   		
   			else echo "<a href='people/profile.php?u=".$random['username']."'><img style='border:none; margin-bottom:10px;' src='images/no_pic.gif'></a><br clear='left'/>";
   			if ($random['sex']==1) echo "<p><b><a class='blue' href='../blog/?u=".$random['username']."'>".$random['name']."</a></b>"; else echo "<p><b><a class='pink' href='../blog/?u=".$random['username']."'>".$random['name']."</a></b>";
   	
   			
   		
   		//	echo "<br/>".strip_tags($random['about'])."</p>";
   			echo "</p>";
   			echo "</center>";
    
   		
   		?>
  <br/>
	<center><a href="advertising.php"><img src="images/youradhere.gif" alt="Your Ad here" style="border:none;"></a></centeR>  
  
  
   </div>
   
   
   
   <div id="main">
<?php echo $message; ?>

   		<h1>WELCOME</h1>
   	
   		
   		
   		<div class="floatbox" align="center"><p>For updates about Olife or to leave comments and suggestions, check out the <b><a class='blue' href="blog/index.php?u=olife">Olife blog</a></b>.</p> </div>  		<p class="welcome">Welcome to Olentangy Life, the first
and only community website tailored 
to Olentangy High School students, 
by Olentangy High School Students. For more information, see the <a class='blue' href="about.php">about</a> page.</p>

<Br clear="left">

<h1>OHShorts</h1>

   <?php
   		$announceSQL="SELECT announcements.content AS content, announcements.heading AS heading, announcements.datetime AS datetime, people.name AS name, people.username AS username FROM announcements LEFT JOIN people ON announcements.creator=people.id WHERE approved='1' ORDER BY datetime DESC";
   		$announceQuery=@mysql_query($announceSQL);
   		if (!$announceQuery) die ('Error with announcement query '.@mysql_error());
   		
   		while ($announcement=@mysql_fetch_array($announceQuery))
   		{
   				echo "<h2 class='gold'>".$announcement['heading']."</h2>";
   				echo "<p><b><a class='blue' href='people/".$announcement['username']."'>".$announcement['name']."</a> - ".date('m.d.y',($announcement['datetime']+(60*60*3)))."</b><br/>";
   				echo $announcement['content']."</p>";
   		}
    ?>
   </p>
   </center>
   
   

<hr height=1 color=#dddddd>
<div align=center>
<script type="text/javascript"><!--
google_ad_client = "pub-3878855217301525";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text";
google_ad_channel ="";
google_color_border = "CCCCCC";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_url = "666666";
google_color_text = "333333";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>




</div>

   <div id="rightbar">
   <center><a href="http://marykay.com/llillemoen"><img src="ad.jpg" style="border:0px;"></a></center>
   <h2 class='gold'>OLIFE NEWS</h2><Br/>

<h2>ALBUMS</h2>

 <p><b>11.27.05</b><br/>
Albums is up, it's pretty sweet.  Basically it's like webshots, except cool. Check it out in 'You'.
</p>






   
    <center><h2 class="gold">ARTICLES</h2><p>
   <?php
   $updateSQL="SELECT title, id, datetime FROM content ORDER BY datetime DESC LIMIT 5";
	$updateQuery=@mysql_query($updateSQL);
	if (!$updateQuery) die ('Error with new update query...'.@mysql_error());
	
	while ($update=@mysql_fetch_array($updateQuery))
	{
		echo "<b><a class='blue' href='content.php?id=".$update['id']."'>".$update['title']."</a></b> ".date('g:iA n/j',$update['datetime'])."<br/>";
	}
	
   ?><Br/>
  <i>If you are interested in writing for Olentangy Life, contact me at the <a href="about.php" class='blue'>about</a> page.</i>
   </p></center>
   
  
   		
   		
  </div>
  
   
  </BODY>
</HTML>