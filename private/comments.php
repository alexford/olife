<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');
$section="blog";

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
   <?php include ('../master_nav.php'); ?>
   
   <div id="privateleftbar" align=right>
   
  
  
	<?php include('privatenav.php'); ?>
   
   </div>
   
   
   
   <div id="mainnoright">

   		<h1>TEN MOST RECENT COMMENTS</h1>
   	<?php
   	
   	$commentSQL="SELECT comments.datetime AS comment_time, comments.comment AS comment, posts.heading AS post_heading, people.username AS commenter_username, people.name AS commenter_name, comments.postid AS id, profile_pics.filename AS pic, comments.id AS comment_id FROM comments
   	LEFT JOIN people ON (comments.person = people.id) 
   	LEFT JOIN posts ON (comments.postid = posts.id)
   	LEFT JOIN profile_pics ON (people.pic = profile_pics.id)
   	WHERE posts.person='".$person['id']."' ORDER BY comment_id DESC LIMIT 10";
   	$commentQuery=@mysql_query($commentSQL);
   	if (!$commentQuery) die ('Error with comments query '.@mysql_error());
   	
   	
   	while ($comment=@mysql_fetch_array($commentQuery))
   	{
   		echo "<div class='box'>";
   		
   		echo "<table cellpadding=5>";
   		echo "<tr><td align='right'><p class='gold'><b>COMMENT FROM:</b></p></td>";
   		echo "<td><p><b><a class='comments' href='../people/profile.php?u=".$comment['commenter_username']."'>".$comment['commenter_name']." (".$comment['commenter_username'].")</a></b></p></td>";
   		echo "</tr>";
   		
   		echo "<tr><td align='right'><p class='gold'><b>ON:</b></p></td>";
   		echo "<td><p><b>".date('l n/j, g:iA',($comment['comment_time']+(60*60*3)))."</b></p></td>";
   		echo "</tr>";
   		


   		echo "<tr><td align='right'><p class='gold'><b>ON POST:</b></p></td>";
   		echo "<td><p><b><a class='comments' href='../blog/post.php?id=".$comment['id']."'>".$comment['post_heading']."</a></b></p></td>";
   		echo "</tr>";
   		
   		echo "<tr><td>";
   		
   			if ($comment['pic']) echo "<tr><td valign=top width=70><a href='../people/profile.php?u=".$comment['commenter_username']."'><img style='border:none; margin-bottom:10px;' src='../people/userpic.php?pic=".$comment['pic']."' align='left'></a><Br/>";
   		
   		
   			else echo "<tr><td width=70 valign=top><a href='../people/profile.php?u=".$comment['commenter_username']."'><img style='border:none; margin-bottom:10px;' src='../images/no_pic.gif' align='left'></a>";
   		
   		
   		echo "</td>";
   		echo "<td><p>".str_replace("\n", "<br />", $comment['comment'])."</p></td></tr>";
   		
   		echo "</table>";
   		
   		echo "</div>";
   	}
   	
  
   	
   	
   	
   	?>
 



</div>

  
  
   
  </BODY>
</HTML>