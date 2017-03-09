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
	$_SESSION['login_redirect']='private/comments.php';
	header("location: ../login.php");
	
}

 $_SESSION['subscribe_redirect']="../private/subscriptions.php";

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
   
   
      echo "<p><b>LOGGED IN AS ".$person['name']." (".$person['username'].") - <a href='../logout.php'>LOGOUT</a></b></p>";
  
   ?>
   
   </div>
   <div id="nav"><a href='../index.php'>HOME</a> <a href='../about.php'>ABOUT</a> <a href="../people/">PEOPLE</a> <a href="../blogs/">BLOGS</a></div>
   
   <div id="privateleftbar" align=right>
   
  
<h2><a class="pnav" href="index.php">POSTS</a></h2>
 	<h2><a class="pnav" href="comments.php">COMMENTS</a></h2>
 	<h2><a class="pnav" href="blog.php">BLOG</a></h2>
 	<h2><a class="pnav" href="profile.php">PROFILE</a></h2>
 	<h2><a class="pnav" href="schedule.php">SCHEDULE</a></h2>
 	<h2><a class="pnav" href="activities.php">ACTIVITIES</a></h2>
 	<h2><a class="pnav" href="subscriptions.php">SUBSCRIPTIONS</a></h2>
   	
   </div>
   
   
   
   <div id="main">

   		<h1>POSTS</h1>
  		<p><i>Posts by the people you are subscribed to.</i></p>
  		<?php
	
		$postsSQL="SELECT people.username, people.sex, people.name, posts.body, posts.heading, posts.datetime, posts.id AS postid, subscriptions.id, profile_pics.filename AS pic FROM subscriptions LEFT JOIN people ON (subscriptions.ee = people.id) LEFT JOIN posts ON (subscriptions.ee = posts.person) LEFT JOIN profile_pics ON (people.pic = profile_pics.id) WHERE subscriptions.er = '".$person['id']."' AND posts.deleted != '1' ORDER BY datetime DESC LIMIT $from, $max_results";
		$postsQuery=@mysql_query($postsSQL);
		if (!$postsQuery) die ('Error with posts query! '.@mysql_error());
	
		while($post=@mysql_fetch_array($postsQuery))
		{
			echo "<table cellspacing=10><td valign=top>";
			
			if ($post['pic']) echo "<a href='../blog/?u=".$post['username']."'><img style='border:none; margin-bottom:10px;' src='../people/userpic.php?pic=".$post['pic']."' align='left'></a><Br/>";
   		
   		
   			else echo "<a href='../blog/?u=".$post['user']."'><img style='border:none; margin-bottom:10px;' src='../images/no_pic.gif' align='left'></a>";
   		
	if ($post['sex']==1) echo "<p style='text-align:right;'><b><a class='blue' href='../blog/?u=".$post['username']."'>".$post['name']."</a></b></p>"; else echo "<p style='text-align:right;'><b><a class='pink' href='../blog/?u=".$post['username']."'>".$post['name']."</a></b></p>";
   		
			echo "</td><td>";
			echo "<h2 class='gold'>".$post['heading']."</h2>";

			echo "<font class='date'>".date('l n/j g:iA',($post['datetime']+(60*60*3)))."</font>";
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
						echo "<a class='comments' href='../blog/post.php?id=".$post['postid']."'>";
			$commentSQL="SELECT COUNT(*) as Num FROM comments WHERE postid='".$post['postid']."'";
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
			
	 
		
   
	
	?>  - <a class='blue' href='../blog/unsubscribe.php?u=<?php echo $post['username']; ?>'>unsubscribe</a>  <?php 
	
	
			echo "</p></p></td></table>";	
			
		}	
		
		// Figure out the total number of results in DB: (TAKEN FROM PHPFREAKS.COM)
		
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM posts WHERE person='".$person['id']."'"),0); 

// Figure out the total number of pages. Always round up using ceil() 
$total_pages = ceil($total_results / $max_results); 

// Build Page Number Hyperlinks 
echo "<center>"; 
echo "<div class='pagelinks'>";
// Build Previous Link 
if($page > 1){ 
$prev = ($page - 1); 
echo "<a href=\"".$_SERVER['PHP_SELF']."?".$blog['username']."page=$prev\">Previous</a> "; 
} 



// Build Next Link 
if($page < $total_pages){ 
$next = ($page + 1); 
echo "<a href=\"".$_SERVER['PHP_SELF']."?".$blog['username']."page=$next\">Next</a>"; 
} 

echo "</div>";
echo "</center>"; 
	?>


</div>

   <div id="rightbar">
   		
   		<h2>SUBSCRIPTIONS</h2>
   		<?php
   			$subsSQL="select distinct people.username as user, people.name as name, people.lastupdate AS lastupdate, subscriptions.id AS sub_id FROM subscriptions LEFT JOIN people ON (subscriptions.ee = people.id) WHERE subscriptions.er = '".$person['id']."' ORDER BY people.lastupdate DESC";
   			$subsQuery=@mysql_query($subsSQL);
   			if (!$subsQuery) die ('Error with subscriptions query '.@mysql_error());
   		
   			while ($subscription=@mysql_fetch_array($subsQuery))
   			{
   				echo "<div class='box'><p><b><a class='blue' href='../blog/?u=".$subscription['user']."'>".$subscription['name']."</a></b><br/><b>Last update:</b><br/>".date('g:iA n/j',($subscription['lastupdate']+(60*60*3)))."</p><p><a class='blue' href='../blog/unsubscribe.php?u=".$subscription['user']."'>unsubscribe</a></p></div>";
   			}
   			
   		
   		?>
 		<h2>SUBSCRIBERS</h2>
 		   <?php
   			$subsSQL="select distinct people.username as user, people.name as name, people.lastupdate AS lastupdate, subscriptions.id AS sub_id FROM subscriptions LEFT JOIN people ON (subscriptions.ee = people.id) WHERE subscriptions.ee = '".$person['id']."' ORDER BY people.lastupdate DESC";
   			$subsQuery=@mysql_query($subsSQL);
   			if (!$subsQuery) die ('Error with subscriptions query '.@mysql_error());
   		
   			while ($subscription=@mysql_fetch_array($subsQuery))
   			{
   				echo "<div class='box'><p><b><a class='blue' href='../blog/?u=".$subscription['user']."'>".$subscription['name']."</a></b><br/><b>Last update:</b><br/>".date('g:iA n/j',($subscription['lastupdate']+(60*60*3)))."</p></div>";
   			}
   			
   		
   		?>
   </div>
  
   
  </BODY>
</HTML>