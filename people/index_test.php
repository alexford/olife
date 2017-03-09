<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT name, class, username FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
	
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
    <TITLE>Olentangy Life :. People</TITLE>
    <link rel="stylesheet" media="screen" href="../olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="../images/olentangy_life.gif"></div>
  
   <div id="tagline">the best olentangy student community ever.</div>
   <div id="bar"></div>
   <div id="login">
   
   <?php
   
   if ($person)
   {
      echo "<p><b>LOGGED IN AS ".$person['name']." (".$person['username'].") - <a href='../private/'>YOU</a> - <a href='../blog/?u=".$person['username']."'>YOUR BLOG</a> - <a href='../logout.php'>LOGOUT</a></b></p>";
    }
    else
    {
    ?>
 
   
   <form method="post" action="../logincheck.php">      
    <p>
		
		<b>USER:</b>
		
		<input type="text" name="username" value="username" size=8>
		
		<b class="gold">PW:</b>
		
		<input type="password" name="password" size=8>
		<?php $_SESSION['login_redirect']="people/"; ?>
		 <input class="submit" type="image" name="login" src="../images/login.gif"> <a href="../register.php"class="submit"><img src="../images/register.gif" alt="register"></a>
		 
    </p>
  </form>
   
   <?php 
   
   }
   
   ?>
   
   
   </div>
   <div id="nav"><a href='../index.php'>HOME</a> <a href='../about.php'>ABOUT</a> <a href="index.php">PEOPLE</a> <a href="../blogs/">BLOGS</a></div>
   
 
   
   
   
   <div id="mainnoright">
   <br/>
  <div class='box'>
  <h2>SORT PEOPLE</h2>
  				<form method="get" action="index.php" style="margin-bottom:0px;">
  				<table>
  				<tr><td><p><b>Name search:</b></p></td><td><input type="text" name="q"></td></tr>
  				<tr><td>
  					<select name="c">
  						<option value="0">Any Grade</option>
  						<option value="9">Freshmen</option>
  						<option value="8">Sophomores</option>
  						<option value="7">Juniors</option>
  						<option value="6">Seniors</option>
  					</select></td><td>
  <?php
  						echo "<select name='a'>";
  						$classSQL="SELECT name, id FROM activities ORDER BY name ASC";
  						$classQuery=@mysql_query($classSQL);
  						if (!$classQuery) die ('Error with class query '.@mysql_error());
  						echo "<option value='0'>Any Activity</option>";
  						while ($class=@mysql_fetch_array($classQuery))
  						{
  							echo "<option value='".$class['id']."'>".$class['name']."</option>";
  						}
  						echo "</select></td>";
  					
  					?>
  					<td><button type="submit">Sort</button></td></tr></table>
  				</form>
</div>
   		
   	<?php
   	
   	if ($_GET['q'])
   	{
   		$whereAdd="(people.name LIKE '%".$_GET['q']."%' OR people.username LIKE '%".$_GET['q']."%') AND ";
   	}
   	if ($_GET['a']>0)
   	{
   		$actSQL="SELECT name FROM activities WHERE id='".$_GET['a']."'";
   		$actQuery=@mysql_query($actSQL);
   		if (!$actQuery) die ('error with activities query '.@mysql_error());
   		$activity=@mysql_fetch_array($actQuery);
   		$activity_name=$activity['name'];
   		if ($_GET['c'])
   		{
   			if ($_GET['c']=='6') $class="SENIORS";
   			if ($_GET['c']=='7') $class="JUNIORS";
   			if ($_GET['c']=='8') $class="SOPHOMORES";
   			if ($_GET['c']=='9') $class="FRESHMEN";
   			$searchSQL="SELECT DISTINCT people.sex AS sex, people.name AS name, people.username AS username, people.class AS class, people.about AS about, profile_pics.filename AS pic, people.lastupdate AS lastupdate, people.joindate AS joindate, people.aim AS aim, people.id AS id
            FROM people LEFT JOIN act_enrollments ON (people.id = act_enrollments.person) 
            LEFT JOIN profile_pics ON (people.pic = profile_pics.id)
		WHERE ".$whereAdd." act_enrollments.activity = '".$_GET['a']."' AND people.class='".$_GET['c']."'";
   			$heading = $class." IN ".$activity_name;
   		}
   		else
   		{
   			$searchSQL="SELECT DISTINCT people.sex AS sex, people.name AS name, people.username AS username, people.class AS class, people.about AS about, profile_pics.filename AS pic, people.lastupdate AS lastupdate, people.joindate AS joindate, people.aim AS aim, people.id AS id
   			FROM people LEFT JOIN act_enrollments ON (people.id = act_enrollments.person)
   			 LEFT JOIN profile_pics ON (people.pic = profile_pics.id)
		WHERE ".$whereAdd." act_enrollments.activity = '".$_GET['a']."'";
   			$heading = "PEOPLE IN ".$activity_name;
   		}
   		
   	
   	}
   	else if($_GET['e']>0)
   	{
   		$actSQL="SELECT name FROM classes WHERE id='".$_GET['e']."'";
   		$actQuery=@mysql_query($actSQL);
   		if (!$actQuery) die ('error with activities query '.@mysql_error());
   		$activity=@mysql_fetch_array($actQuery);
   		$activity_name=$activity['name'];
   		if ($_GET['c'])
   		{
   			if ($_GET['c']=='6') $class="SENIORS";
   			if ($_GET['c']=='7') $class="JUNIORS";
   			if ($_GET['c']=='8') $class="SOPHOMORES";
   			if ($_GET['c']=='9') $class="FRESHMEN";
   			$heading = $class." IN ".$activity_name;
   			$searchSQL="SELECT DISTINCT people.sex AS sex, people.name AS name, people.username AS username, people.class AS class, people.about AS about, profile_pics.filename AS pic, people.lastupdate AS lastupdate, people.joindate AS joindate, people.aim AS aim, people.id AS id
   			FROM people LEFT JOIN enrollments ON (people.id = enrollments.person)
   			 LEFT JOIN profile_pics ON (people.pic = profile_pics.id)
		WHERE ".$whereAdd." enrollments.class = '".$_GET['e']."' AND people.class='".$_GET['c']."'";
   		}
   		else
   		{
   			$heading = "PEOPLE IN ".$activity_name;
   			$searchSQL="SELECT DISTINCT people.sex AS sex, people.name AS name, people.username AS username, people.class AS class, people.about AS about, profile_pics.filename AS pic, people.lastupdate AS lastupdate, people.joindate AS joindate, people.aim AS aim, people.id AS id
   			FROM people LEFT JOIN enrollments ON (people.id = enrollments.person)
   			 LEFT JOIN profile_pics ON (people.pic = profile_pics.id)
		WHERE ".$whereAdd." enrollments.class = '".$_GET['e']."'";
   		}	
   	
   	}
   	else if ($_GET['c']>0)
   	{
   			if ($_GET['c']=='6') $class="SENIORS";
   			if ($_GET['c']=='7') $class="JUNIORS";
   			if ($_GET['c']=='8') $class="SOPHOMORES";
   			if ($_GET['c']=='9') $class="FRESHMEN";
   			$heading = $class;
   			$searchSQL="SELECT DISTINCT people.sex AS sex, people.name AS name, people.username AS username, people.class AS class, people.about AS about, profile_pics.filename AS pic, people.lastupdate AS lastupdate, people.joindate AS joindate, people.aim AS aim, people.id AS id
		 FROM people LEFT JOIN profile_pics ON (people.pic = profile_pics.id)
		WHERE ".$whereAdd." people.class = '".$_GET['c']."'";
   	}
   	else
   	{
   	$heading = "ALL PEOPLE";
   		$searchSQL="SELECT DISTINCT people.sex AS sex, people.name AS name, people.username AS username, people.class AS class, people.about AS about, profile_pics.filename AS pic, people.lastupdate AS lastupdate, people.joindate AS joindate, people.aim AS aim, people.id AS id
   		
   		FROM people  LEFT JOIN profile_pics ON (people.pic = profile_pics.id)";
   		if ($whereAdd) $searchSQL=$searchSQL." WHERE ".$whereAdd." 1=1";
   	}
   	
   	echo "<h1 style='text-transform:uppercase;'>".$heading."</h1>";
   	// Figure out the total number of results in DB: (TAKEN FROM PHPFREAKS.COM)

  	if (!$_GET['by']) 
 		$searchSQL=$searchSQL." ORDER BY name ASC";
   	else
   	{
   		$searchSQL=$searchSQL." ORDER BY ".$_GET['by']." ASC";
   	
   	}
   	
   	$allQuery=@mysql_query($searchSQL);
   	if (!$allQuery) die ('error with query 1 '.@mysql_error());
   	$all_results=@mysql_num_rows($allQuery);
   	
   	$searchSQL=$searchSQL." LIMIT $from, $max_results";
   	$searchQuery=@mysql_query($searchSQL);
   	if (!$searchQuery) die ('Error with search query '.@mysql_error());

		
$total_results = $all_results;
//echo $total_results." ";
// Figure out the total number of pages. Always round up using ceil() 
$total_pages = ceil($total_results / $max_results); 
//echo $total_pages;
// Build Page Number Hyperlinks 



   	echo "<p><b>Results: ".($from+1)." - ";
   	
   	if (($from+$max_results)>$all_results) echo $all_results;
   		else echo ($from+$max_results);
   	echo " of ".$all_results." ";
   	// Build Previous Link 
if($page > 1){ 
$prev = ($page - 1); 
echo "<a class='blue' href=\"".$_SERVER['PHP_SELF']."?a=".$_GET['a']."&c=".$_GET['c']."&e=".$_GET['e']."&q=".$_GET['q']."&page=$prev\">Previous</a> "; 
} 



// Build Next Link 
if($page < $total_pages){ 
$next = ($page + 1); 
echo "<a class= 'blue' href=\"".$_SERVER['PHP_SELF']."?a=".$_GET['a']."&c=".$_GET['c']."&e=".$_GET['e']."&q=".$_GET['q']."&page=$next\">Next</a>"; 

} 

   	
   	
   	echo "</b></p>";
   	
  
   	while ($member=@mysql_fetch_array($searchQuery))	
   	{
   		if ($member['class']=='6') $class="senior";
   		if	($member['class']=='7') $class="junior";
   		if	($member['class']=='8') $class="sophomore";
   		if	($member['class']=='9') $class="freshman";
   		echo "<div class='userbox'><table cellspacing=5>";
   		if ($member['pic']) echo "<tr><td valign=top width=70><a href='profile.php?u=".$member['username']."'><img style='border:none; margin-bottom:10px;' src='userpic.php?pic=".$member['pic']."' align='left'></a><Br clear='left' />";
   		
   		
   			else echo "<tr><td width=70 valign=top><a href='profile.php?u=".$member['username']."'><img style='border:none; margin-bottom:10px;' src='../images/no_pic.gif' align='left'></a><br clear='left'/>";
   		
    
    
   		
   		
   		
   		
   		 echo "</td>";
   		echo "<td valign=top>"; 
   		echo "<h2 style='text-transform:uppercase; align:left;'>";
   		if ($member['sex']==1) echo "<a class='blue' href='profile.php?u=".$member['username']."'>"; else echo "<a class='pink' href='profile.php?u=".$member['username']."'>";
   		
   		
   		echo $member['name']."</a></h2>";
   		echo "<p style='margin-bottom:0px;'><b>".$member['username']." - ".$class." - joined ".date("m/d/y",$member['joindate'])."</b></p>";
   		echo "<p style='margin-bottom:0px;'>".strip_tags(str_replace("\n", " ", $member['about']."<br/>")) ."</p>";
   		 
  
   		
   		
   		echo "</td></tr></table>";
   		echo "</div>";
   		
   	}
   
 
   	echo "<p><b>Results: ".($from+1)." - ";
   	
   	if (($from+$max_results)>$all_results) echo $all_results;
   		else echo ($from+$max_results);
   	echo " of ".$all_results." ";
   	// Build Previous Link 
if($page > 1){ 

echo "<a class='blue' href=\"".$_SERVER['PHP_SELF']."?a=".$_GET['a']."&c=".$_GET['c']."&e=".$_GET['e']."&page=$prev\">Previous</a> "; 
} 



// Build Next Link 
if($page < $total_pages){ 

echo "<a class= 'blue' href=\"".$_SERVER['PHP_SELF']."?a=".$_GET['a']."&c=".$_GET['c']."&e=".$_GET['e']."&page=$next\">Next</a>"; 

} 

   	
   	
   	echo "</b></p>";
 
		
   	?>
   		

</div>

  <div id="leftbar">
 <p><b>Use the drop downs</b> to the to right to select a grade, an activity, or both to sort through members on Olentangy Life.</p>
 <p><b>Click on the member's name</b> to see their profile.</p>
  </div>
   
   
  </BODY>
</HTML>