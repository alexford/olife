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
	
	$picSQL="SELECT filename FROM profile_pics WHERE id='".$person['pic']."'";
	$picQuery=@mysql_query($picSQL);
	if (!$picQuery) die ('Error with pic query '.@mysql_error());
	$pic=@mysql_fetch_array($picQuery);
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
   <?php include ('../master_nav.php'); ?>
   
   <div id="privateleftbar" align=right>
   
  
  
	<?php include('privatenav.php'); ?>
   	
   
   </div>
   
   
   
   <div id="main">
	<h1>YOU ARE <?php echo $person['name']; ?></h1>
	<div class='box' style='float:right;'>
	<h2>THE NEW 'YOU'</h2>
	<p>'You' has been reorganized into three sections:<Br/><br/>
	<b>You</b> - Your profile, activities, schedule, etc<Br/><br/>
	<b>Blog</b> - Your blog posts, comments, and settings<br/><br/>
	<b>Messages</b> - Your message center<br/><br/>
	<b>Contribute</b> - Contribute to Olife</p>
	
	
	</div>
	<h2 class='gold'>PROFILE</h2>	<p><?php echo $person['profile']; ?></p>
	<p><b><a class='blue' href='profile.php'>edit profile</a></b></p>
</div>

<div id="rightbar">
<?php if ($person['pic']) { ?>
  		 <a href="../pics<?php echo $person['pic']; ?>" target="_BLANK"><img src="../blog/blogpic.php?pic=<?php echo $pic['filename']; ?>" align=left style="margin-right:10px;"></a>
   <?php } ?>
   	
<br clear='left'/>  
<p><b><?php echo $person['username']; ?></b><br/><Br/>

<i><?php echo $person['about']; ?></i>

<b><a class='blue' href='profile.php'>edit profile</a></b></p>

<h2 class='gold'>SCHEDULE</h2>
<?php
$myEnrollSQL="SELECT enrollments.id AS en_id, classes.name AS class_name, teachers.name AS teacher_name, enrollments.teacher AS teacher_id, enrollments.class AS class_id, enrollments.period AS period_id,  periods.display AS period
FROM enrollments
LEFT JOIN classes on (enrollments.class = classes.id)
LEFT JOIN teachers on (enrollments.teacher = teachers.id)
LEFT JOIN periods on (enrollments.period = periods.id)

WHERE semester ='2' AND person='".$_SESSION['uid']."' ORDER BY periods.id ASC";


$classesQuery=@mysql_query($myEnrollSQL);
if (!$classesQuery) die ('Error with classes query '.@mysql_error());
echo "<p>";
while ($class=@mysql_fetch_array($classesQuery))
{
	echo "<b>".$class['class_name']."</b> - ".$class['period']."<br/>";

}
echo "<b><a class='blue' href='schedule.php'>view/edit schedule</a></b>
</p>";

?>

<h2 class='gold'>ACTIVITIES</h2>
<?php
$myEnrollSQL="SELECT activities.name AS name, activities.id AS id FROM act_enrollments 
   	LEFT JOIN activities ON (activities.id = act_enrollments.activity)
   	WHERE (act_enrollments.person='".$person['id']."') ORDER BY name ASC";


$classesQuery=@mysql_query($myEnrollSQL);
if (!$classesQuery) die ('Error with activities query '.@mysql_error());
echo "<p>";
while ($class=@mysql_fetch_array($classesQuery))
{
	echo "<b>".$class['name']."</b><Br/>";

}
echo "<b><a class='blue' href='activities.php'>view/edit activities</a></b>
</p>";

?>
</div>
 
  
   
  </BODY>
</HTML>