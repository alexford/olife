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
   
   
   
   <div id="mainnoright">

   		<h1>BLOG SETTINGS</h1>
   		<p><b><a class='pnav' href="../blog/?u=<?php echo $person['username']; ?>">GO TO YOUR BLOG</a></b></p>
   		<p>Your blog can be accessed at <a class='blue' href='../blog/?u=<?php echo $person['username'];?>'>http://www.olentangylife.com/<?php echo $person['username']; ?></a>, unless it has spaces in it. Shame on you. If it does, you can get it here <a class='blue' href='../blog/?u=<?php echo $person['username'];?>'>http://www.olentangylife.com/blog/?u=<?php echo $person['username']; ?></a> </p>
   		<form method="post" action="saveblog.php">
   		<table>
   		<tr><td><p><b>THEME</b><br/>Choose a theme for the colors of your blog. The ability to create your own
   		themes will be available eventually. <b>If you also select a skin (by clicking 'skin' on the left side of your screen), it will override this theme.</b> Also, there will be new themes posted all the time, and by all the time, I mean
   		when I feel like it.</p></td>
   		<td>
   	<?php //theme select box
   		echo "<select name='theme'>";
   		$themeSQL="SELECT publicname, id FROM templates WHERE public ='1'";
   		$themeQuery=@mysql_query($themeSQL);
   		if (!$themeQuery) die ('error with theme query '.@mysql_error());
   		echo "<option value='".$person['theme']."'>No Change</option>";
   		while ($theme=@mysql_fetch_array($themeQuery))
   		{
   			echo "<option value='".$theme['id']."'>".$theme['publicname']."</option>";
   		}
 	
  		echo "</select>";
   		
   	?></tr></tr>
   	
   	<tr valign=center>
	<td><p><b>HEADLINE</b><br/>A slogan.</p></td>
	<td valign=top><input size='30' type="text" name="headline" value="<?php echo $person['headline']; ?>"></td>
</tr>

	<tr valign=center>
	<td><p><b>TAGLINE</b><br/>A counter slogan. These are both at the top of your blog (if you're using the default theme).</p></td>
	<td valign=top><input size='30' type="text" name="tagline" value="<?php echo $person['tagline']; ?>"></td>
</tr>

	<tr valign=center>
	<td></td>
	<td valign=top><p><input type="checkbox" name="allow_comments" value="1" <?php if ($person['allow_comments']!=0) echo "CHECKED"; ?>>Allow comments on my blog.</p></td>
</tr>


<tr><td></td><td><button type="submit">Save and go to blog</button></td></tr>
 		</table>
 		
</form>


</div>

  
  
   
  </BODY>
</HTML>