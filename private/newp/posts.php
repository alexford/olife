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
   <div id="nav"><a href='../index.php'>HOME</a> <a href='../about.php'>ABOUT</a> <a href="../people/">PEOPLE</a> <a href="../blogs/">BLOGS</a></div>
   
   <div id="privateleftbar" align=right>
   
  
  
	<?php include('privatenav.php'); ?>
   	
   
   </div>
   
   
   
   <div id="mainnoright">

   		<h1>NEW POST</h1>
   	
   		<form method='post' action='postupdate.php'>
   			<table class="form">
   			<tr>
   				<td align="right"><p><b>HEADING (optional):</b></p></td>
   				<td><input type="text" name="heading" value="My Post" size="32"></td>
   			</tr>
   			
   			<tr>
   				<td valign="top" align="right"><p><b>BODY (required):</b></p></td>
   				<td><textarea rows="14" cols="40" name="body">This is my post. I can use any HTML here if I know what I'm doing.</textarea></td>
   			</tr>
   			
   			<tr>
   				<td valign="top" align="right"><p><b>TAKE ME:</b></p></td>
   				<td>
   					<table>
   					<tr><td><input type="radio" name="redirect" value="blog" checked></td>
   						<td><p><b>To My Blog</b></p></td></tr>
   					<tr><td><input type="radio" name="redirect" value="private" checked></td>
   						<td><p><b>Back Here</b></p></td></tr>
   					</table>
   				</td>
   			</tr>
   			<tr><td></td>
   				<td><button type="submit">POST</button</td>
   			</tr>
   			</table>
   		</form>
   		
   		

<h1>CURRENT POSTS</h1>
<?php
	
		$postsSQL="SELECT * FROM posts WHERE person = '".$person['id']."' ORDER BY datetime DESC LIMIT $from, $max_results";
		$postsQuery=@mysql_query($postsSQL);
		if (!$postsQuery) die ('Error with posts query! '.@mysql_error());
	
		while($post=@mysql_fetch_array($postsQuery))
		{
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
			
			if ($post['deleted']==1)
				echo " - <a class='comments' href='undelete.php?id=".$post['id']."'>undelete</a>";	
			else
				echo " - <a class='comments' href='delete.php?id=".$post['id']."'>delete</a>";	
				
			echo " - <a class='comments' href='edit.php?id=".$post['id']."'>edit</a>";
			echo "</p></p>";	
			
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

 
  
   
  </BODY>
</HTML>