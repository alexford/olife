<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');

$section="you";
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
	$_SESSION['login_redirect']='private/comments.php';
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
   <div id="bar"></div>
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
   
   
   
   <div id="mainnoright">

   		<h1>YOUR PROFILE</h1>
   		<p><b><a class='pnav' href="../people/profile.php?u=<?php echo $person['username']; ?>">GO TO YOUR PROFILE</a></b></p>
   		<p>Your profile can be accessed at <a class = 'blue' href="../people/profile.php?u=<?php echo $person['username']; ?>">http://www.olentangylife.com/people/<?php echo $person['username']; ?>/</a></p>
   		<form method="post" action="savepro.php" enctype="multipart/form-data">
   		<table>

  	<tr valign=center>
	<td><p><b>YOUR NAME</b><br/>This <i>can</i> be your first name only, but putting your full name is encouraged. Pretty soon I'm going to contact/annihilate the users without so much as a first name, so be warned.</p></td>
	<td valign=top><input size='30' type="text" name="name" value="<?php echo $person['name']; ?>"></td>
</tr>

   	<tr valign=center>
	<td><p><b>YOUR AIM</b><br/>Your AIM screenname. If you don't have one, why not? AOL will control the world soon enough, peon. There will be support for other messengers later.</p></td>
	<td valign=top><input size='30' type="text" name="aim" value="<?php echo $person['aim']; ?>"></td>
</tr>

	<tr valign=center>
	<td><p><b>YOUR PICTURE</b><br/>This picture identifies you. It doesn't have to be a picture of you. It DOES have to be a .jpg, no bigger than 100k in size. If it's explicit, maybe I'll give you pizza, maybe I'll come over and punch a peperroni through your head.</p>


	
	</td>
	<td valign=top><input type="file" name="image"  size="15"></td>
</tr>

<tr valign=center>
	<td><p><b>YOUR PROFILE</b><br/>Write a few words about yourself. Try not to be too conceited. Oh, who am I kidding. HTML is allowed.</p></td>
	<td valign=top><textarea rows='10' cols='30' name="profile"><?php echo $person['profile']; ?></textarea></td>
</tr>

<tr valign=center>
	<td><p><b>ABOUT TEXT</b><br/>This is like a shorter, abbreviated version of you. It goes under your picture and on various people search pages, including your public profile.</p></td>
	<td valign=top><textarea rows='10' cols='30' name="about"><?php echo $person['about']; ?></textarea></td>
</tr>

<tr valign=center>
	<td><p><b>OTHER JOURNAL</b><br/>If you have another journal, enter the <b>FULL</b> URL here (i.e. http://xanga.com/myjournal/). If you don't want anyone to read your other journal, don't enter the URL here.</p></td>
	<td valign=top><input size='30' type="text" name="journal" value="<?php echo $person['journal']; ?>"></td>
</tr>

<tr><td></td><td><button type="submit">Save and go to profile</button></td></tr>
 		</table>
 		
</form>


</div>

  
   
  </BODY>
</HTML>