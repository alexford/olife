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
    <TITLE>Olentangy Life :. Advertising</TITLE>
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
   <center><img src="images/bigad.gif" alt="Your ad here"></center>
  
   
  
   </div>
   
   
   
   <div id="mainnoright">
	<h1>Advertising at www.olentangylife.com</h1>
	<h2>What is Olentangy Life?</h2>
	<p>Olentangy Life is a community/blogging site created for Olentangy High School students. It allows
	each member (all OHS students) their own blog page and profile.  The students can post to their blogs 
	and leave comments for each other, and members are linked together based on their classes and activities,
	forming a deep community that the members continue to return to.  The site was created by Alex Ford, an
	OHS senior.</p>
	
<h2>Background</h2>
	<p>Olentangy Life was created in Summer 2005, and thanks to word of mouth alone had 450 users in one week after its late August launch. 
	It is always being updated with new features/areas that keep members returning, and currently (10/7/2005)
	hosts 652 members' blog pages and profiles.  The website and its creator have been featured in the Olentangy 
	Oracle and its popularity from both parents and students is expected to grow.</p>
	
<h2>Demographic Information/Statistics</h2>
	<p>Olentangy Life visitors are mostly local high school students (from both Olentangy and Liberty high schools) 
	ages 14-18, but parents, family, friends from other schools, as well as OHS faculty are also visitors to the site.
	</p>
	<p>The interests/spending habits of this demographic means that your advertisement will be seen by your potential customers.
	You are not wasting money on advertising seen by individuals who are outside of your target demographic.
	</p>
	
	<div class='box'><p><b>Statistics for the month of September 2005:</b><br/>
		Successful requests for pages (hits): 786,377<br/>
		Average successful requests for pages per day: 26,294<br/>
		Distinct hosts served (individual people on the website): 3,011<br/></p>
		
		<p>These statistics are expected to increase for the month of October due to upcoming features and newspaper coverage.</p>
	</div>
<h2>Your Ads</h2>
	<p>Your ads (for custom advertising design, see below) will be placed on Olentangy Life's home and people search pages as well as member profile pages (not blogs).
	Any page at www.olentangylife.com with a grey block O in the background (with the exception of members' control panels) will have your ad, depending on the number of advertisers and space available.
	The ads will go on either the left or right column of the page, taking up about 20% of the width of the page. You will probably share adspace with
	other local businesses, but not competitors. If you have a website, your ad will link to your website (ask me about developing your website for you if you don't currently
	have one). </p>

	<p>If you do not have an ad designed or suitable for web use, I will personally custom design you an advertisement to your specifications for a flat rate. </p>
	
<h2>Rates</h2>
	<p>All rates are negotiable based on your company's size and height of ad.</p>

<h2>Contact</h2>
	<p>Alex Ford, founder/creator<br/>
	<a class='blue' href="mailto:alex@olentangylife.com">alex@olentangylife.com</a></p>
	




</div>


   
  </BODY>
</HTML>