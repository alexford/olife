<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');

$section="you";

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

   		<h1>YOUR ACTIVITIES</h1>
   		<p>At this page, you can enroll in and unenroll from the various activities offered at OHS. The activities you select here will be visible on your blog as well as your profile.  Members can be grouped
   		by activities.</p>
   	
   		<div class="box">
   			<h2>ENROLL IN AN ACTIVITY</h2>
   		<p>Please be honest and accurate with your activities.</p>
   		<form method="post" action="act_enroll.php">
   		<table><tr><td valign='center'><p><b>ACTIVITY:</b></p></td><td valign='center'>
   		<?php //theme select box
   		echo "<select name='activity'>";
   		$themeSQL="SELECT name, id FROM activities ORDER BY name ASC";
   		$themeQuery=@mysql_query($themeSQL);
   		if (!$themeQuery) die ('error with theme query '.@mysql_error());
   		while ($theme=@mysql_fetch_array($themeQuery))
   		{
   			echo "<option value='".$theme['id']."'>".$theme['name']."</option>";
   		}
 	
  		echo "</select>";
   		
   	?></td></tr>
   	<tr><td></td><td><button type="submit">Enroll</button</td></tr>
   	</table>
   		</form>
   	</div>
   	
   	<h1>CURRENT ACTIVITIES</h1>
   	<p>You are currently enrolled in:</p>
   	<?php
   	$actSQL="SELECT activities.name AS name, activities.id AS id FROM act_enrollments 
   	LEFT JOIN activities ON (activities.id = act_enrollments.activity)
   	WHERE (act_enrollments.person='".$person['id']."') ORDER BY name ASC";
   	$actQuery=@mysql_query($actSQL);
   	if (!$actQuery) die ('Error with act query '.@mysql_error());
   	
   	if (mysql_num_rows($actQuery)<1)
   		echo "<p><i>No activities</i></p>";
   	else
   	{
   		while ($activity=@mysql_fetch_array($actQuery))
   		{
   			echo "<div class='box'>";
   			echo "<h2>".$activity['name']."</h2>";
   			echo "<p><a class='blue' href='../people/?a=".$activity['id']."'>others in ".$activity['name']."</a> - <a class='blue' href='delete_act.php?id=".$activity['id']."'>unenroll</a></p>";
   			$announceSQL="SELECT people.name AS person, people.username AS username, act_posts.heading AS heading, act_posts.body AS body, act_posts.datetime AS datetime FROM act_posts LEFT JOIN people on (act_posts.person=people.id) WHERE act_posts.activity='".$activity['id']."'";
   			$announceQuery=@mysql_query($announceSQL);
   			if (!$announceQuery) die ('Error with announcements '.@mysql_error());
   			if (@mysql_num_rows($announceQuery)>0)
   			{
   				echo "<br/><p class='blue'><b>ANNOUNCEMENTS</b></p>";
   				echo "<p><i>Remember, anyone enrolled in the activity can post these announcements.</i></p><br/>";
   				while ($announcement=@mysql_fetch_array($announceQuery))
   				{
   					echo "<div class='indent'>";
   					echo "<p><b>".$announcement['heading']."</b></p>";
   					echo "<p>".$announcement['body']."<Br/><Br/><i>Posted by <a class='blue' href='http://olentangylife.com/people/profile.php?u=".$announcement['username']."'>".$announcement['person']."</a></p>";
   					echo "</div>";
   				}
   			
   			}
   			
   			echo "</div>";
   		}
   	}

?>
</div>


  
   
  </BODY>
</HTML>