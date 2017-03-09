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
$max_results = 20; 

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

   		<h1>BLOG SKIN</h1>
   		<p>Skins are ready, and there shall be much rejoicing ('yay...'). Here's the deal.  If you select a <b>skin</b> (completely new look for your blog), it overrides your <b>theme</b> (color scheme for default theme). There aren't
   		that many skins to choose from right now, but I and others will be adding more. Know HTML/CSS? Talk to me about making skins. It's easy, I promise.</p>
   		
   		<h2 class='gold'>Choose a skin...</h2><Br/>
   		<?php
   		$skinSQL="SELECT skins.name AS name, skins.id AS id, skins.description AS description, people.name AS creator_name, people.username AS creator_username FROM skins LEFT JOIN people ON (people.id = skins.creator) WHERE skins.public='1' ORDER BY id DESC LIMIT $from, $max_results";
   		$skinQuery=@mysql_query($skinSQL);
   		if (!$skinQuery) die ('Error with skins query '.@mysql_error());
   		echo "<table cellspacing=5 width=100%>";
   		while ($skin=@mysql_fetch_array($skinQuery))
   		{
   			echo "<tr>";
   			echo "<td width=20% valign=top><p style='text-align:right;'><b>".$skin['name']."</b><br/>by <a class='gold' href='../people/profile.php?u=".$skin['creator_username']."'>".$skin['creator_name']."</a></p></td>";
   			echo "<td valign=top style='border-left:1px solid #ccc; padding-left:5px;'>";
   			echo "<p>".$skin['description']."<br/><a class='blue' href='../blog/?u=".$person['username']."&s=".$skin['id']."' target='_blank'>preview on your blog</a> - ";
   			
   			if ($person['skin']!=$skin['id']) echo "<a href='saveskin.php?id=".$skin['id']."' class='blue'>use this skin</a></i></p></td>";
   				else echo "<b>you are currently using this skin</b> - <a href='unskin.php' class='blue'>remove it</a></i></p></td>";
   			
   			echo "</tr>";
 		}
 		echo "</table>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM skins WHERE public='1'"),0); 

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

  
  
   
  </BODY>
</HTML>