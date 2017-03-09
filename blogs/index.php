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
	
function tokenizeQuoted($string)
{
   for($tokens=array(), $nextToken=strtok($string, ' '); $nextToken!==false; $nextToken=strtok(' '))
   {
       if($nextToken{0}=='"')
           $nextToken = $nextToken{strlen($nextToken)-1}=='"' ? 
               substr($nextToken, 1, -1) : substr($nextToken, 1) . ' ' . strtok('"');
       $tokens[] = $nextToken;
   }
   return $tokens;
}

	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>Olentangy Life :. Blogs</TITLE>
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
   
   	
   	$nummessagesQuery=@mysql_query("SELECT id FROM messages WHERE (recipient='".$person['id']."' OR recipient='all') AND deleted!='1' AND unsent!='1' AND messages.read!='1'");
   	if (!$nummessagesQuery) die ('error with new messages query'.@mysql_error());
   	$messages=@mysql_num_rows($nummessagesQuery);
   	
      echo "<p><b>LOGGED IN AS ".$person['name']." (".$person['username'].") - <a href='../private/'>YOU</a> - <a href='../blog/?u=".$person['username']."'>YOUR BLOG</a> - <a href='../logout.php'>LOGOUT</a></b><Br/>You have ".$messages." unread <a href='/private/messages.php' class='blue'>message(s)</a>.</p>";
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
  <?php include ("../master_nav.php"); ?>
 
   
   
   
   <div id="mainnoright">
    <h1>BLOGS</h1>
	  <div class='box'>
	 
  <h2>SEARCH BLOG POSTS</h2>
  				<form method="get" action="index.php" style="margin-bottom:0px;">
  				<table>
  				<tr><td><p>Search:</p></td><td><input type="text" name="q" value="<?php if ($_GET['q']) echo $_GET['q']; ?>"></td></tr>
  				<tr><td></td><td><p><input type="radio" name="l" value="and" <?php if (!$_GET['l'] || $_GET['l']=='and') echo "CHECKED"; ?>> all of these words<Br/><input type="radio" name="l" value="or" <?php if ($_GET['l']=='or') echo "CHECKED";?>> any of these words</p></td></tr>
  				<tr><td></td><td><button type="submit">Search</button></td></tr></table>
  				</form>
</div>


 <?php
 	if ($_GET['q'])
 	{
 		
echo "<h1>SEARCH RESULTS</h1>";
 		$terms=tokenizeQuoted($_GET['q']);
  		if ($_GET['l']=='or')
  			$logic="OR";
  		else
  			$logic="AND";
  			
  		$whereclause="WHERE ";
  		if ($logic=="OR") $whereclause=$whereclause."(1=0) ";
  		else			 $whereclause=$whereclause."(1=1) ";
  		
  		foreach($terms AS $term)
  		{
  			$whereclause=$whereclause.$logic." body LIKE '% ".$term." %' ";
  		
  		}
  		
  		$whereclause=$whereclause."AND posts.deleted !='1'";
 		
 		$sql="SELECT posts.id AS id, posts.heading AS heading, posts.body AS body, profile_pics.filename AS pic, posts.datetime AS datetime, people.name AS poster_name, people.username AS poster_username FROM posts LEFT JOIN people ON (people.id = posts.person) LEFT JOIN profile_pics ON (profile_pics.id=people.pic) ".$whereclause." ORDER BY datetime DESC LIMIT $from, $max_results";
 		$searchQuery=@mysql_query($sql);
 		if (!$searchQuery) die ('Error with search query '.@mysql_error());
 		
 		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM posts ".$whereclause),0); 

// Figure out the total number of pages. Always round up using ceil() 
$total_pages = ceil($total_results / $max_results); 
 	
 		echo "<center><p><b><i>".$total_results." results,  newest posts first</i></b></p></center>";
 		while ($post=@mysql_fetch_array($searchQuery))
 		{
 			echo "<div class='box'>";
 			
 			echo "<table width=100%><td valign='top'><h2 class='gold'><a class='blue' href='../blog/post.php?id=".$post['id']."'>".$post['heading']."</a></h2></td>";
			echo "<td valign='top'><p style='text-align:right;'><b><a class='blue' href='../people/profile.php?u=".$post['poster_username']."'>".$post['poster_name']."</a><br/>".date('l n/j g:iA',($post['datetime']+(60*60*3)))."</p></td></table>";
			//if ($post['pic'])
			//	echo "<img align='left' style='margin-right:10px;' src='../people/userpic.php?pic=".$post['pic']."'>";
				
			
			$theString = str_replace("\n", " ", "<p>".substr(strip_tags($post['body']),0,700)."<a class='blue' href='../blog/post.php?id=".$post['id']."'>...read more</a><br/>");	
			$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
	$smileysQuery=@mysql_query($smileysSQL);
	if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
	
	
	while ($smiley=@mysql_fetch_array($smileysQuery))
	{
		$theString=str_replace($smiley['ocode'],"<img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'>",$theString);
		
	}
	
	echo $theString;
	
			echo "</p></div>";	
			
 		}
 		
 		//echo $whereclause;
     	//echo $sql;
     	
     	
     	
     	
     	
     echo "<center><p><b>";	
   	// Build Previous Link 
if($page > 1){ 

echo "<a class='blue' href=\"".$_SERVER['PHP_SELF']."?q=".$_GET['q']."&l=".$_GET['l']."&page=".($page-1)."\">Previous</a> "; 
} 



// Build Next Link 
if($page < $total_pages){ 

echo "<a class= 'blue' href=\"".$_SERVER['PHP_SELF']."?q=".$_GET['q']."&l=".$_GET['l']."&page=".($page+1)."\">Next</a>"; 
     }
     
    echo "</b></p></center>";
     }
 ?> 	
</div>

  <div id="leftbar">


     <center><h2 class='gold'>TOP 5 POSTERS</h2><Br/>
  <?php $visitedSQL="SELECT people.username, people.name, people.id, COUNT(posts.id) as num FROM people  LEFT JOIN posts on (posts.person = people.id) WHERE posts.deleted != '1' GROUP BY people.username ORDER BY num DESC LIMIT 5
";
  		$visitedQuery=@mysql_query($visitedSQL);
  		while ($member=@mysql_fetch_array($visitedQuery))
  		{	echo "<p><b><a href='../blog/?u=".$member['username']."' class='blue'>".$member['name']."</a> - ".$member['num']."</b></p>";
  		}
  	?></center>
<center> <a href="http://marykay.com/llillemoen"><img src="../ad.jpg" style="border:0px;"></a><br/></center>
 
  </div>
	
  
   
  </BODY>
</HTML>