<?php

session_start();
header("cache-control:private");

include ('dbconnect.php');


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
    <TITLE>Olentangy Life :. Home</TITLE>
    <link rel="stylesheet" media="screen" href="olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="images/olentangy_life.gif"></div>
  
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
		 <input class="submit" type="image" name="login" src="images/login.gif"> <a href="register.php"class="submit"><img src="images/register.gif" alt="register"></a>
		 
    </p>
  </form>
   
   <?php 
   
   }
   
   ?>
   
   
   </div>
   <div id="nav"><a href='index.php'>HOME</a> <a href='about.php'>ABOUT</a> <a href="people/">PEOPLE</a> <a href="blogs/">BLOGS</a></div>
   
   <div id="leftbar">
   
  
   <center><h2 class="gold">JUST UPDATED</h2>
   <p>
	<?php
	
	$updateSQL="SELECT username, lastupdate FROM people WHERE lastupdate>0 ORDER BY lastupdate DESC";
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
	echo "<p>".$num['num']." total users</p>";
	
	?>
   </p></center>
   
  
   </div>
   
   
   
   <div id="main">
<center>
<table cellspacing=10><td class='box' style="border:1px solid #000;"><h2>I HAVE MY SCHEDULE</h2><p><a class='blue' href='register.php'>Click here</a> to register!</p></td>
<td class='box'><h2>I DON'T HAVE MY SCHEDULE YET</h2><p>Wait until you have your schedule, then come back.</p></td></table>
</center>
   		<h1>WELCOME</h1>
   	
   		
   		
   		<p class="welcome">Welcome to Olentangy Life, the first
and only community website tailored 
to Olentangy High School students, 
by Olentangy High School Students.</p>


<h1>NEWS</h1>
	 <p>Expect relevant OHS news to be here.</p>

</div>

   <div id="rightbar">
   		
   		<div class="newsbox"><h1 class="white">UPDATES</h1>
   		<?php
   		
   			$newsSQL="SELECT * FROM news ORDER BY id DESC";
   			$newsQuery=@mysql_query($newsSQL);
   			if (!$newsQuery) die ('Error with news query...'.@mysql_error());
   			
   			while ($news=@mysql_fetch_array($newsQuery))
   			{
   				echo "<h3>".$news['heading']." (".$news['date'].")</h3>";
   				echo "<p>".$news['news']."</p>";
   			}
   		?>
   				
   	</div>
  
   
  </BODY>
</HTML>