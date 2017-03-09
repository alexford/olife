<?php

session_start();
header("cache-control:private");

include ('dbconnect.php');
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
	echo "<b>".$num['num']."</b> posts</p>";
	
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
   	
   			
   		
   			echo "<br/>".strip_tags($random['about'])."</p>";
   			echo "</center>";
    
   		
   		?>
  
   </div>
   
   
   
   <div id="main">
<?php echo $message; ?>

   		<h1>WELCOME</h1>
   	
   		
   		
   		<p class="welcome">Welcome to Olentangy Life, the first
and only community website tailored 
to Olentangy High School students, 
by Olentangy High School Students. This website was created as an open community for Olentangy High School students. For more information, see the <a class='blue' href="about.php">about</a> page.</p>

<Br clear="left">
<h1>NEWS</h1>
<h2>SOME UPDATES</h2>

 <p><b>Alex Ford - 10.3.05</b><br/>
	You can now search people by username and real name on the people page (a much requested feature).  Also, there will be articles that show up on the right side, written by OHS students. If you have any to contribute, let me know.

</p>

<h2>WHATS NEXT?</h2>

 <p><b>Alex Ford - 10.2.05</b><br/>
	What do you want to see on Olife? Picture hosting/galleries? Private messages? Forums? More news/feature stories? <a href="/people/sambapati87" class="blue">Tell me</a>.
	<?php //include('poll/index.php'); ?>
</p>


<h2>FAKE USERNAMES</h2>

 <p><b>Alex Ford - 9.8.05</b><br/>
	Deleting fake usernames and banning their creators is getting old. You're not funny and you're not fooling anyone, espescially me.  Just don't do it.
</p>

<h2>SUBSCRIPTIONS WORK!</h2>

 <p><b>Alex Ford - 9.5.05</b><br/>
	Subscriptions now work! <a class='blue' href="private/subscriptions.php">Check it out</a>.
</p>


<h2>PRAYER FOR HURRICANE VICTIMS</h2>

 <p><b>Alex Ford - 9.5.05</b><br/>
	 <a class="blue" href="http://www.olentangylife.com/people/bigmanbrad">Brad McCallion</a> (freshman) has written a prayer for the hurricane Katrina victims. Check it out <a class="blue" href="http://olentangylife.com/blog/post.php?id=2159">here</a>.
</p>

<h2>TWO WEEKS INTO SCHOOL UPDATE</h2>

 <p><b>Alex Ford - 9.2.05</b><br/>
	Hey everyone, about two weeks into school we have 557 users and over 6,000 comments.  I think that's pretty impressive.  There havent been a lot
	of changes to the site recently; I've been pretty busy. I plan on adding tools to block users from commenting on your blog and things like that, when I get the chance.
	 Until then, I'm off to the football game. <b>Go Braves!</b>
</p>

<h2>FOUR HUNDRED FIFTY USERS</h2>

 <p><b>Alex Ford - 8.23.05</b><br/>
	In one week after launch, Olentangy Life has 450 users.  As of May of 2005, there were 1,124 students enrolled at OHS <a href="http://www.olentangy.k12.oh.us/pdf/enroll/052005enrollment.pdf" class='blue'>(source)</a>. That's 40% of all students at OHS, if you want to look at it that way. It's probably a little less because there are more freshman this year than there were seniors last year, but at least a third.  How 'bout it?<Br/>

</p>



</div>

   <div id="rightbar">
   
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
   
   <center><h2 class='gold'>ANNOUNCEMENTS</h2><p>
   <?php
   		$announceSQL="SELECT announcements.content AS content, announcements.heading AS heading, announcements.datetime AS datetime, people.name AS name FROM announcements LEFT JOIN people ON announcements.creator=people.id WHERE approved='1' ORDER BY datetime DESC";
   		$announceQuery=@mysql_query($announceSQL);
   		if (!$announceQuery) die ('Error with announcement query '.@mysql_error());
   		
   		while ($announcement=@mysql_fetch_array($announceQuery))
   		{
   				echo "<b style='color:#222;'>".$announcement['heading']."</b><br/>";
   				echo "<b>".$announcement['name']." - ".date('g:iA n/j',($announcement['datetime']+(60*60*3)))."</b><br/>";
   				echo $announcement['content']."<br/><br/>";
   		}
    ?>
   </p>
   </center>
   		
   		
  </div>
  
   
  </BODY>
</HTML>