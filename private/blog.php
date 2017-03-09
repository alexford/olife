<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');

$section="blog";

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
   <?php include ('../master_nav.php'); ?>
   
   <div id="privateleftbar" align=right>
   
<?php include('privatenav.php'); ?>
   </div>
   
   
   
   <div id="main">
	<h1>YOUR BLOG</h1>
	<p><b><a class='blue' href='../blog/?u=<?php echo $person['username']; ?>'>goto your blog</a></b></p>
	
	
	
	<table width=100%><td><h2>LAST POST</h2></td><td><div align='right'>
<p style='margin-bottom:0px;'><b><a class='blue' href='posts.php'>view all posts</a></b></p></div></td></table>
	<div class='box'>
	<?php $lastSQL="SELECT * FROM posts WHERE person='".$person['id']."' AND deleted!='1' ORDER BY datetime DESC LIMIT 1";
	      $lastQuery=@mysql_query($lastSQL);
	      if (!$lastQuery) die ('Error with last post query '.@mysql_error());
	      
	      $post=@mysql_fetch_array($lastQuery);
	      
	      echo "<h2 class='gold'>".$post['heading']."</h2><font class='date'>".date('l n/j g:iA',($post['datetime']+(60*60*3)))."</font>";
			echo "<p ";
			
			if ($post['deleted']==1) echo "class='deleted'";
			
			echo ">";
			$theString = str_replace("\n", "<br />", $post['body']."</p>");	
			$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
	$smileysQuery=@mysql_query($smileysSQL);
	if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
	
	
	while ($smiley=@mysql_fetch_array($smileysQuery))
	{
		$theString=str_replace($smiley['ocode'],"<img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'>",$theString);
		
	}
	
	echo $theString;
	
			echo "<p>";
			echo "<a class='comments' href='../blog/post.php?id=".$post['id']."'>";
			$commentSQL="SELECT COUNT(*) as Num FROM comments WHERE postid='".$post['id']."'";
			$commentQuery=@mysql_query($commentSQL);
			if (!$commentQuery) die ('Error with comment query '.@mysql_error());
			$comment=@mysql_fetch_array($commentQuery);
			$numcomments=$comment['Num'];
			echo $numcomments;
			if ($numcomments > 1 || $numcomments == 0)
				echo " comments";
			else
				echo " comment";
			echo "</a>";
			
			
				echo " - <a class='comments' href='delete.php?id=".$post['id']."'>delete</a>";	
				
			echo " - <a class='comments' href='edit.php?id=".$post['id']."'>edit</a>";
			echo "</p></p>";	
   	
   	?>
</div>

<table width=100%><td><h2>LAST COMMENT</h2></td><td><div align='right'>
<p style='margin-bottom:0px;'><b><a class='blue' href='comments.php'>view all comments</a></b></p></div></td></table>
<?php
$commentSQL="SELECT comments.datetime AS comment_time, comments.comment AS comment, posts.heading AS post_heading, people.username AS commenter_username, people.name AS commenter_name, comments.postid AS id, profile_pics.filename AS pic, comments.id AS comment_id FROM comments
   	LEFT JOIN people ON (comments.person = people.id) 
   	LEFT JOIN posts ON (comments.postid = posts.id)
   	LEFT JOIN profile_pics ON (people.pic = profile_pics.id)
   	WHERE posts.person='".$person['id']."' ORDER BY comment_id DESC LIMIT 1";
   	   	$commentQuery=@mysql_query($commentSQL);
   	if (!$commentQuery) die ('Error with comments query '.@mysql_error());
   	
   	
	$comment=@mysql_fetch_array($commentQuery);
   	
   	echo "<div class='box'>";
   		
   		echo "<p>".str_replace("\n", "<br />", $comment['comment'])."</p><Br/>";
   		echo "<p><b><i>from <a class='blue' href='../people/profile.php?u=".$comment['commenter_username']."'>".$comment['commenter_username']."</a> (".$comment['commenter_name'].") on post <a href='../blog/post.php?id=".$comment['id']."' class='blue'>".$comment['post_heading']."</a></i></b></p>";
   		echo "</div>";

?>
</div>



  <div id="rightbar">
  <center>
  <p style='margin-bottom:0px; color:#444;'><b><?php echo $person['headline']; ?></b></p>
	<p style='color:#555;'><i><?php echo $person['tagline']; ?></i></p>

  <h2 class='gold'>BLOG STATS</h2>
  <p>visited <b><?php echo $person['visited'];?> times</b><Br/>
 <?php $postsSQL="SELECT Count(*) AS num FROM posts WHERE person='".$person['id']."' AND deleted !='1' ORDER BY datetime DESC";
$postsQuery=@mysql_query($postsSQL);
if (!$postsQuery) die ('error with posts query '.@mysql_error());
$postCount=@mysql_fetch_array($postsQuery);

echo "has <b>".$postCount['num']."</b> posts<br/>";
?>

<?php if ($person['lastupdate']) { ?>last post on <b><?php echo date("m/d/y",$person['lastupdate']); ?></b><Br/><?php } ?>

<?php

if ($person['skin'])
{
	$skinQuery=@mysql_query("SELECT name FROM skins WHERE id = '".$person['skin']."'");	
	if (!$skinQuery) die ('Error with skin query '.@mysql_error());
	$skin = @mysql_fetch_array($skinQuery);
	
	echo "using skin <b>".$skin['name']."</b>";
}

?>
 	

  
  </p>
  </center>
  </div>
  
   
  </BODY>
</HTML>