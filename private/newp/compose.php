<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');
$section="messages";


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
   
   
      echo "<p><b>LOGGED IN AS ".$person['name']." (".$person['username'].") - <a href='../logout.php'>LOGOUT</a></b></p>";
  
   ?>
   
   </div>
   <div id="nav"><a href='../index.php'>HOME</a> <a href='../about.php'>ABOUT</a> <a href="../people/">PEOPLE</a> <a href="../blogs/">BLOGS</a></div>
   
   <div id="privateleftbar" align=right>
   
  
  
	<?php include('privatenav.php'); ?>
   	
   
   </div>
   
   
   
   <div id="mainnoright">
	<?php
		if ($_GET['replyid'])
		{
			$postsSQL="SELECT messages.recipient AS recipient, messages.subject AS subject, people.username AS sender_uname FROM messages LEFT JOIN people ON (messages.sender = people.id) WHERE messages.id='".$_GET['replyid']."' AND messages.unsent!='1'";
			$postsQuery=@mysql_query($postsSQL);								//do the query
			if (!$postsQuery) die ('Error with posts query '.@mysql_error());
			$message=@mysql_fetch_array($postsQuery);
			if ($message['recipient']!=$person['id'] && $message['recipient']!='all') die("<p>You are not authorized to view (or reply to) this message.</p></div>");
	
			$sendTo=$message['sender_uname'];
			$subject="re: ".$message['subject'];
		}
		
		if ($_GET['to'])
		{
			$sendTo=$_GET['to'];
		}
	?>

   		<h1>SEND A MESSAGE</h1>
   	
   		<form method='post' action='confirmmessage.php'>
   			<table class="form">
   			<tr>
   				<td valign="top" align="right"><p><b>SEND TO:</b></p></td>
   				<td><input type="text" name="recipient" value="<?php echo $sendTo; ?>" size="16"><p>Enter all or part of a username or name. If an exact username is not entered, Olife will search for the member you are looking for.</td>
   			</tr>
   			<tr>
   				<td valign="top" align="right"><p><b>SUBJECT:</b></p></td>
   				<td><input type="text" name="subject" value="<?php echo $subject; ?>" size="32"></td>
   			</tr>
   			
   			<tr>
   				<td valign="top" valign="top" align="right"><p><b>MESSAGE:</b></p></td>
   				<td><textarea rows="14" cols="40" name="message">Type your message here.</textarea></td>
   			</tr>
   			
   			<tr><td></td>
   				<td><button type="submit">SEND</button</td>
   			</tr>
   			</table>
   			<input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
   		</form>
   		
   		


</div>

 
  
   
  </BODY>
</HTML>