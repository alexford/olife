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
   
   
   
   <div id="mainnoright">
	
	<h1>HTML/OCODE</h1>
   	 <p>Basicall (<i>I'm taking the effort to type this, but not add that y</i>), you type the code and you see whats to the right of it.</p>
   	 <table cellspacing=10>
   	 <td valign=top>
   	 <h2>SMILEYS</h2>
   	<table cellspacing=10 class=box>
   
<td><p><b>CODE</b></p></td><td><p><b>SMILEY</b></p></td></tr>
<?php
	
	$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
	$smileysQuery=@mysql_query($smileysSQL);
	if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
	
	
	while ($smiley=@mysql_fetch_array($smileysQuery))
	{
	

		echo "<td><p>".$smiley['ocode']."</p></td>";
		echo "<td><img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'></td></tr>";
		
	}
	


?>
</td>
</table>
  <td valign=top>
  <h2>HTML/PICTURES</h2>
  <table cellspacing=10 class=box>
  <td><p><b>CODE</b></p></td><td><p><b>EXAMPLE</b></p></td></tr>
  <td><p>&lt;B&gt;The Text&lt;/B&gt;</p></td><td><p><b>The Text</b></p></td></tr>
  <td><p>&lt;I&gt;The Text&lt;/I&gt;</p></td><td><p><i>The Text</i></p></td></tr>
  <td><p>&lt;U&gt;The Text&lt;/U&gt;</p></td><td><p><u>The Text</u></p></td></tr>
  <td><p>&lt;MARQUEE&gt;The Text&lt;/MARQUEE&gt;</p></td><td><p><MARQUEE><p>The Text</p></MARQUEE></p></td></tr>
  <td><p>&lt;IMG SRC = 'http://www.olentangylife.com/picture.jpg' /&gt;</p></td><td><img src='http://www.olentangylife.com/picture.jpg' /></td></tr>
  <td><p>&lt;FONT COLOR = 'RED'&gt;The Text&lt;/FONT&gt;<br/><i>(Black, silver, gray, white, maroon, red, purple,<Br/>fuchsia, green, lime, olive, yellow, navy, blue, teal, and aqua also work)</i></p></td><td><p><font color='red'>The Text</font></p></td></tr>
  </table>
  </td>
  
  </table>
   	
</div>

  
  
   
  </BODY>
</HTML>