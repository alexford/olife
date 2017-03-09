<?php

session_start();
header("cache-control:private");

include ('dbconnect.php');

	
	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>Olentangy Life :. Register</TITLE>
    <link rel="stylesheet" media="screen" href="olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="images/olentangy_life.gif"></div>
  
   <div id="tagline">the best olentangy student community ever.</div>
   <div id="bar"></div>
   <div id="login">
   
   
   
   </div>
   <div id="nav"><a href='index.php'>HOME</a> <a href='about.php'>ABOUT</a> <a href="people/">PEOPLE</a> <a href="blogs/">BLOGS</a></div>
   
   <div id="leftbar" align='right'>
   
  
   <center><h2 class="gold">REGISTER</h2>
   <p>
	This page will get you started with a blog
	and profile at Olentangy Life.  Please be sure to 
	fill in all fields <i>accurately</i> and completely,
	<i>espescially</i> on the schedule.<br/><br/>
	
	<b>NOTE:<br/>
	If you are not a current Olentangy High School student,
	please do not register. If any account is found to have been not
	created by an Olentangy student, it will be deleted immediately.</b><br/><br/>
	
	Please remember that anything you post on Olentangy Life (and the internet in general) is public
	and can be read by anyone, whether they are registered here or not (including your parents). You
	are liable for any and all content you submit to this website.<br/><Br/>
	
	If you have any problems registering or have any questions, please
	ask them as instructed by the <a href="about.php">about</a> page.
	</p>
	
   </center>
   </div>
   
   
   
   <div id="main">
	<p><b>SENIOR SHOOTOUT PEOPLE:</b></p> <P>Congratulations, you made 
it 
this 
far and are well on your way to competence. You'll have to bear with me 
(Alex, not DJ, as I'm the one breathing life into Olife (which we all know 
is dead) so senior shootout can go smoothly (HA), again) as we get you all
signed up for a month or so of steady BS and cruelty. Sign up here, then 
return to shootout.olentangylife.com to log in. Odds are you won't be able 
to do anything yet.</p>
	<form method='post' action='submitinfo.php'>
	<h1>BASIC INFORMATION</h1>
<div class="box">

<table cellpadding=5px>

<tr valign=center>
	<td><p><b>Your Name:</b><br/>At least your first name, but full names are encouraged. Try not to use crazy characters like hearts, but its alright if you do.</p></td>
	<td valign=top><input type="text" name="name"></td>
</tr>

<tr valign=center>
	<td><p><b>Choose a username:</b><br/>This is your "screenname" on olife. DONT use crazy characters. Stick to letters, numbers, underscores, and spaces.</p></td>
	<td valign=top><input type="text" name="username"></td>
</tr>

<tr valign=center>
	<td><p><b>Gender:</b></p></td>
	<td valign=top><p><input type="radio" name="sex" value="1">M <input type="radio" name="sex" value="0">F</p></td>
</tr>

<tr valign=center>
	<td><p><b>Your AIM:</b><br/>If you don't have/use AIM, leave this blank for now.</p></td>
	<td valign=top><input type="text" name="aim"></td>
</tr>

<tr valign=center>
	<td><p><b>Your Email Address:</b><br/>Needed for announcements and such.</p></td>
	<td valign=top><input type="text" name="email"></td>
</tr>

<tr valign=center>
	<td><p><b>Confirm Email Address:</b><br/>Type it again to make sure you typed it right.</p></td>
	<td valign=top><input type="text" name="emailc"></td>
</tr>

<tr valign=center>
	<td><p><b>Choose a Password:</b><br/>Don't forget this.</p></td>
	<td valign=top><input type="password" name="pw"></td>
</tr>

<tr valign=center>
	<td><p><b>Confirm Password:</b><br/>Type it again.</p></td>
	<td valign=top><input type="password" name="pwc"></td>
</tr>

</table>

</div>
<div align='center'><button type="submit">Continue...</button></div>
	</form>
   		

</div>

   <div id="rightbar">
   		
   				
   	</div>
  
   
  </BODY>
</HTML>
