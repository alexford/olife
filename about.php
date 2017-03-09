<?php

session_start();
header("cache-control:private");

include ('dbconnect.php');


if ($_SESSION['uid'])
{$uinfoSQL="SELECT name, class, username, id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
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
   <div id="bar"></div>
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
   <center>
   	<h2 class='gold'>VIEWING</h2>
  	<p>This site is best viewed at at least 1024x768 resolution using a standards compliant browser like Firefox. If you are using IE (and you probably are) I highly reccomend
  	 switching to Firefox for the best experience at this site. The site has been designed to work fine in IE, though, so whatever floats your boat.</p>
  	 
  	 
  	 <script type="text/javascript"><!--
google_ad_client = "pub-3878855217301525";
google_ad_width = 125;
google_ad_height = 125;
google_ad_format = "125x125_as_rimg";
google_cpa_choice = "CAAQo-aZzgEaCM3CU97Siy5UKK2293M";
//--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>   <br/><Br/>
<h2>THANKS</h2>
<p>...to Jerrod Morgan and Andrew Hazlett for helping with color schemes</p>
<p>...to Anna Betchel for helping with skins</p>
<p>...to Olentangy for support</p>
<p>...to anyone else who helped me and I didn't mention here</p>
  </center>
   </div>
   
   
   
   <div id="main">
<h1>ABOUT OLENTANGY LIFE</h1>

<h2>BACKGROUND</h2>
<p>
This site was created as an alternative to Xanga, LiveJournal, etc.,
specifically (and exclusively) for Olentangy High School students.  If you don't know, these sites
allow each registered user (usually for free) to have their own weblog posted
online.  Everyone gets a profile and sometimes can upload a picture of themselves 
for their page(s).  While these services are free, their large size and hundreds
of thousands of users forces them to put ads on their pages (including the users'
blogs) to pay for hosting the site.  These sites are self-described as community
sites, but the community environment gives way to a huge group of people that may
or may not know each other, coupled with the fact that users must sift through
ads to get to said community.</p>

<p>Olentangy Life's community is stronger than previous services because at its
peak registration, it would only have to serve a little over a thousand students.
This allows for any ads that may appear to be placed only on Olentangy Life's
homepage.  <i>Members will never have ads on their pages</i>. We can do this 
because the number of members turns into the number of hits which turns into
hosting costs.  A site the size of Olentangy Life costs very little to run.</p>

<p>As such, in early August 2005 this idea was hatched and development begun.</p>


<h2>FEATURES</h2>
<p>Olentangy Life offers the following features normally found in huge, ad-driven
sites, plus some features that you won't find on Xanga or LiveJournal.<br/><br/>

	 -- <b>Full featured personal blog</b><br/>
	 		- Blog can be customized through colors, title, etc.<br/>
	 		- 'Profile Pics' can be uploaded/changed an unlimited
	 			number of times<br/>
	 		- Posts can be deleted or modified by the poster, and if you change
	 			your mind you can bring them right back again.<br/>
	 		- Any logged in member can leave comments on any other blog,
	 			which in turn links the other blog back to you.<br/>
	 		- Any logged in member can subscribe to any other blog for
	 			the latest posts from that member.<br/><br/>
	 -- <b>Automatic Social Networking</b><br/>
	 		- Olentangy Life automatically connects members through their
	 			grade, classes, and activities.<br/>
	 		- As soon as you register, you get a complete list of other
	 			members that are in your classes.  <br/>
	 		- Each blog has a list of activities that user is involved in.<br/><br/>
	 -- <b>Personal profile/AIM directory</b><br/>
	 		- Each user has a personal profile, with their name, picture, email,
	 			AIM, and general information. <br/>
	 		- This allows a quick overview of registered members, with links
	 			to their blogs.  Meet people you may or may not have known,
	 			and be assured that they go to your school.<br/><br/>
	 -- <b>Owned and operated by OHS student(s).</b> <br/>
	 		- If you have any questions or have a feature you'd like to see,
	 		  you know exactly who to talk to, and who you talk to is
	 		  most likely the person that will personally be taking care
	 		  of it.<br/>
	 		- The site was not created by a faceless corporation or business 
	 		  of any kind with the goal of generating ad revenue. It was 
	 		  created and tested by the people that will use it.<br/>
	 		  
<h2>CONTACT</h2>
<p>This website was created in its entirety by Alex Ford, a current OHS senior.
Any and all inquiries about the website can be directed toward him at
	<a class=blue href="mailto:alex@olentangylife.com">alex@olentangylife.com</a>, or on AIM: sambapati87.</p>

<h1>QUESTIONS</h1>

<p><b>CAN I REGISTER?</b><br/> If you are an Olentangy High School student, please do! Even
if you never update your blog, your scheduling information and activites help
to create connections between you and your peers. If you don't go to OHS, please
do not register. You can still read everyones blog and view their profiles, however.</p>

<p><b>HOW DO I REGISTER?</b><br/> <a class='blue' href="register.php">Click here</a>.</p>

<p><b>IT DIDNT WORK!</b><br/> Try again, then contact me as described above.</p>

<p><b>WHERE IS MY SCHEDULE/HOW DO I POST TO MY BLOG?</b><br/> <a class='blue' href="private/">Click here.</a> It's all there.</p>

<p><b>I NOTICE A MEMBER WHO I SUSPECT IS NOT AN OHS STUDENT. WHAT SHOULD I DO?</b><br/> Tell me. I'll check it out.</p>

<p><b>ANOTHER MEMBER IS LEAVING ABUSIVE OR VULGAR COMMENTS ON MY BLOG.</b><br/> Members can say what they want, but in
extreme cases, let me know.</p>

<p><b>THIS IS STUPID!</b><br/> Thank you. Next?</p>


<h1>DISCLAIMER/RULES</h1>
<p>
This website and all of its content, either of the website itself or on the blogs of its members has no affiliation with, is not sponsored or endorsed by, and does not reflect the views of Olentangy Local School District. Any statements by the sites owner or the sites members, anywhere on the site, do not reflect the opinions or views of any collective body, including OHS students or faculty.<br/><br/>

Any member has the ability to post anything, for any reason, with the understanding that anything posted is the legal and ethical responsibility of the individual. I am not responsible for anything vulgar, explicit, illegal, or in any other way offensive content posted by students at OHS.<br/><br/>

Members must take responsibility for their own voices. Remember that <i>anyone</i> with an internet connection (parents, teachers, and police officers, for example) can and probably will read everything you post.  Also, be aware that it is the policy of our school district that any teacher who in any way finds out something that may compromise the safety of students is required to report it to the school. <i>This applies here as well</i>. Think before you post, and remember that posting here has the effect of yelling your opinion down the hallway at school.<br/><br/>


That being said, no content posted by members of this site will be edited or changed unless said content is of a physically threatening manner that compromises the safety of students, contains nudity of any kind, or contains copyrighted material for which the poster does not hold copyright.  Exceptions to this rule can be made at any time at my discretion. Examples of exceptions include repeated posts/comments, abuse or overloading of this website (purposefully or accidentally), attempting to "hack" this website, or compromising the privacy of any Olentangy Life member besides yourself. Any member that does any of the above-mentioned things will first have the offending action (post or comment) deleted.  A second offense will result in an IP ban.<br/><Br/>

By participating in Olentangy Life, you are agreeing to these terms.<br/><br/>

-Alex Ford
</p>






</div>

   <div id="rightbar">
   		
   		<center>
   		<a href="http://marykay.com/llillemoen"><img src="ad.jpg" style="border:0px;"></a><br/>
   		<img src="http://www.php.net/images/logos/php-power-white.gif" ALT="PHP"><br/><br/>
   		<img src="images/mysql.gif" ALT="PHP"><br/><br/>
  
  		<a href="http://www.dreamhost.com/rewards.cgi?alexford"><img boder="0" src="http://www.dreamhost.com/images/rewards/88x31-a.gif" alt="DreamHost Hosting"></a><Br/><br/>
  
   		<a href="http://www.dreamhost.com/donate.cgi?id=3200"><img border="0" 
alt="Donate towards my web hosting bill!" src="https://secure.newdream.net/donate1.gif" /></a><Br/><br/>
   		
   		<script type="text/javascript"><!--
google_ad_client = "pub-3878855217301525";
google_ad_width = 120;
google_ad_height = 240;
google_ad_format = "120x240_as";
google_ad_type = "text";
google_ad_channel ="";
google_color_border = "336699";
google_color_bg = "FFFFFF";
google_color_link = "0000FF";
google_color_url = "008000";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

   		</center>
   		</div>
  
   
  </BODY>
</HTML>