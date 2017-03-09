<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT name, class, username, id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
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
   <div id="nav"><a href='../index.php'>HOME</a> <a href='../about.php'>ABOUT</a> <a href="../people/">PEOPLE</a> <a href="../blogs/">BLOGS</a></div>
   
   <div id="privateleftbar" align=right>
   
	<?php include('privatenav.php'); ?>
   
   </div>
   
   
   
   <div id="main">
	
		<h1>CONTRIBUTE</h1>
		<p>From this page you can contribute to Olentangy Life in different ways. Right now, you can submit announcements for the front page. Later, there will be other options.</p>
   		<div class='box'>
   		<h2>SUBMIT AN ANNOUNCEMENT</h2>
   		<p>Do you have an upcoming game or concert, sports scores, or anything else you think OHS students should know? Submit it here. Relevant announcements will be put on the front page following approval.</p>
   		
   		<form method='post' action='postannouncement.php'>
   			<table class="form">
   			<tr>
   				<td align="right"><p><b>HEADING (required):</b></p></td>
   				<td><input type="text" name="heading" value="Announcement Heading" size="32"></td>
   			</tr>
   			
   			<tr>
   				<td valign="top" align="right"><p><b>BODY (required):</b></p></td>
   				<td><textarea rows="14" cols="40" name="body">Type your announcement here.</textarea></td>
   			</tr>
   			
   			<tr><td></td>
   				<td><button type="submit">CONTRIBUTE</button</td>
   			</tr>
   			</table>
   		</form>
   		</div>
   		



</div>

<div id="rightbar">
<center>
<p><b style="color:#333;">WRITE ARTICLES</b></p>
<p>If you are interested in writing articles for Olife, please let me know (my username is sambapati87).</p>
<p><b style="color:#333;">SPREAD THE WORD</b></p>
<p>The success of Olentangy Life depends on the students. If you know someone who's never seen this, let them know about it!</p>
<p><b style="color:#333;">DONATE MONEY</b></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
</form>
<p>While this website incurs no costs, many hours were spent on its creation. It is free for use by any Olentangy Student, but PayPal donations are accepted.</p>
</center>
</div>
 
  
   
  </BODY>
</HTML>