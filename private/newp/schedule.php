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
   <div id="nav"><a href='../index.php'>HOME</a> <a href='../about.php'>ABOUT</a> <a href="../people/">PEOPLE</a> <a href="../blogs/">BLOGS</a></div>
   
   <div id="privateleftbar" align=right>
   
	<?php include('privatenav.php'); ?>
   
   </div>
   
   
   
   <div id="mainnoright">

   		<h1>YOUR SCHEDULE</h1>
   		<p>At this page, you can enroll in and unenroll from classes at OHS. There is a list of your current enrollments as well as a list of people from Olentangy Life that are
   		in your classes. There were quite a few people screwing up this part of registration so I went ahead and re-worked this page.</p>
   	
   		<div class="box">
   			<h2>ENROLL IN A CLASS</h2>
   		<p>Please be honest and accurate with your classes.</p>
   		<form method="post" action="enroll.php">
   		<table><tr><td valign='center'><p><b>CLASS:</b></p></td><td valign='center'>
   		<?php //theme select box
   		echo "<select name='class'>";
   		$themeSQL="SELECT name, id FROM classes ORDER BY name ASC";
   		$themeQuery=@mysql_query($themeSQL);
   		if (!$themeQuery) die ('error with theme query '.@mysql_error());
   		while ($theme=@mysql_fetch_array($themeQuery))
   		{
   			echo "<option value='".$theme['id']."'>".$theme['name']."</option>";
   		}
 	
  		echo "</select>";
  		

   		
   	?></td></tr>
   	
   		<tr><td><p><b>TEACHER:</b></p></td><td>
   		<?php //theme select box
   		echo "<select name='teacher'>";
   		echo "<option value='0'>None/Lunch</option>";
   		$themeSQL="SELECT name, id FROM teachers ORDER BY name ASC";
   		$themeQuery=@mysql_query($themeSQL);
   		if (!$themeQuery) die ('error with theme query '.@mysql_error());
   			
   		while ($theme=@mysql_fetch_array($themeQuery))
   		{
   			echo "<option value='".$theme['id']."'>".$theme['name']."</option>";
   		}
 	
  		echo "</select>";
  			
   		
   	?></td></tr>
   	<tr><td><p><b>PERIOD:</b></p></td><td>
   		<?php //theme select box
   		echo "<select name='period'>";
   		$themeSQL="SELECT display, id FROM periods ORDER BY id ASC";
   		$themeQuery=@mysql_query($themeSQL);
   		if (!$themeQuery) die ('error with theme query '.@mysql_error());
   		while ($theme=@mysql_fetch_array($themeQuery))
   		{
   			echo "<option value='".$theme['id']."'>".$theme['display']."</option>";
   		}
 	
  		echo "</select>";
  			
   		
   	?></td></tr>
   	
   	 	<tr><td><p><b>SEMESTER:</b></p></td><td>
   		<select name='semester'>
   		<option value='0'>Both</option>
   		<option value='1'>1st</option>
   		<option value='2'>2nd</option>
   		</select>
   		</td></tr>
   	
   	
   	
   	<tr><td></td><td><button type="submit">Enroll</button></td></tr>
   	</table>
   		</form>
   	</div>
   	
   	<h1>YOUR SCHEDULE</h1>
   	<p>You are currently enrolled in:</p><center>
   <table cellspacing=10 cellpadding=10>
<td width=50%  valign="top">
<h2>SEMESTER ONE</h2>
<?php //get semester one enrollments

$myEnrollSQL="SELECT enrollments.id AS en_id, classes.name AS class_name, teachers.name AS teacher_name, enrollments.teacher AS teacher_id, enrollments.class AS class_id, enrollments.period AS period_id,  periods.display AS period
FROM enrollments
LEFT JOIN classes on (enrollments.class = classes.id)
LEFT JOIN teachers on (enrollments.teacher = teachers.id)
LEFT JOIN periods on (enrollments.period = periods.id)

WHERE semester ='1' AND person='".$_SESSION['uid']."' ORDER BY periods.id ASC";



$myEnrollQuery=@mysql_query($myEnrollSQL);
if (!$myEnrollQuery) die ('Error with your sem 1 enrollments: '.@mysql_error());

while ($parentData=@mysql_fetch_array($myEnrollQuery)) //for each of my classes
{
	echo "<div class='box'>";
	echo "<p><b>".$parentData['period']." - ".$parentData['class_name']."</b><br/>".$parentData['teacher_name']."</p>";
	echo "<div class='indent'>";
	
		$matesSQL="SELECT DISTINCT people.name AS mate_name,people.username AS mate_username, people.sex AS mate_sex FROM enrollments 
		LEFT JOIN people ON (enrollments.person = people.id) 
		WHERE (enrollments.teacher='".$parentData['teacher_id']."')
		AND (enrollments.person!='".$_SESSION['uid']."')
		AND (enrollments.semester='1')
		AND (enrollments.class='".$parentData['class_id']."')
		AND (enrollments.period='".$parentData['period_id']."')";
	
		$matesQuery=@mysql_query($matesSQL);
		if (!$matesQuery) die ('Error with mates query '.@mysql_error());
		echo "<p><b>";
		if (@mysql_num_rows($matesQuery)>0)
		{
		while ($mateData=@mysql_fetch_array($matesQuery)) //for each of my classmates
		{
			
			
		if ($mateData['mate_sex']==1) echo "<a class='blue'"; else echo "<a class='pink'";
			echo " href='../people/profile.php?u=".$mateData['mate_username']."'>".$mateData['mate_name']."</a></font> ";
			
		
		} //for each of my classmates END
		}
		echo "</b></p>";
		
	echo "</div>";

	echo "<Br/><Br/><p><b><a class='gold' href='delete_enrollment.php?id=".$parentData['en_id']."'>unenroll (this semester only)</a></b></p></div>";
} //for each of my classes END 
?>
</td>
<td width=50% class="goldbox" valign="top">
<h2>SEMESTER TWO</h2>
<?php //get semester two enrollments

$myEnrollSQL="SELECT enrollments.id AS en_id, classes.name AS class_name, teachers.name AS teacher_name, enrollments.teacher AS teacher_id, enrollments.class AS class_id, enrollments.period AS period_id,  periods.display AS period
FROM enrollments
LEFT JOIN classes on (enrollments.class = classes.id)
LEFT JOIN teachers on (enrollments.teacher = teachers.id)
LEFT JOIN periods on (enrollments.period = periods.id)

WHERE semester ='2' AND person='".$_SESSION['uid']."' ORDER BY periods.id ASC";



$myEnrollQuery=@mysql_query($myEnrollSQL);
if (!$myEnrollQuery) die ('Error with your sem 1 enrollments: '.@mysql_error());

while ($parentData=@mysql_fetch_array($myEnrollQuery)) //for each of my classes
{
	echo "<div class='box'>";
	echo "<p><b>".$parentData['period']." - ".$parentData['class_name']."</b><br/>".$parentData['teacher_name']."</p>";
	echo "<div class='indent'>";
	
		$matesSQL="SELECT DISTINCT people.name AS mate_name, people.username AS mate_username, people.sex AS mate_sex FROM enrollments 
		LEFT JOIN people ON (enrollments.person = people.id) 
		WHERE (enrollments.teacher='".$parentData['teacher_id']."')
		AND (enrollments.person!='".$_SESSION['uid']."')
		AND (enrollments.semester='2')
		AND (enrollments.class='".$parentData['class_id']."')
		AND (enrollments.period='".$parentData['period_id']."')";
	
		$matesQuery=@mysql_query($matesSQL);
		if (!$matesQuery) die ('Error with mates query '.@mysql_error());
		echo "<p><b>";
		if (@mysql_num_rows($matesQuery)>0)
		{
		while ($mateData=@mysql_fetch_array($matesQuery)) //for each of my classmates
		{
			
			
			if ($mateData['mate_sex']==1) echo "<a class='blue'"; else echo "<a class='pink'";
			echo " href='../people/profile.php?u=".$mateData['mate_username']."'>".$mateData['mate_name']."</a></font> ";
			
			
		
		} //for each of my classmates END
		}
		echo "</b></p>";
		
	echo "</div>";

	echo "<Br/><Br/><p><b><a class='gold' href='delete_enrollment.php?id=".$parentData['en_id']."'>unenroll (this semester only)</a></b></p></div>";
} //for each of my classes END 
?>

</td>
</table>
</center>
</div>

  
   
  </BODY>
</HTML>