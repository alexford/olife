<?php

session_start();

if (!$_GET['u']) echo "no u"; //header("Location: ../index.php");

include ('../dbconnect.php');
$bloginfoSQL="SELECT visited, name, skin, username, class, aim, email, allow_comments, sex, people.id AS id, headline, tagline, about, journal, profile_pics.filename AS pic, profile_pics.filesize AS pic_size, profile_pics.filetype AS pic_type, theme FROM people
LEFT JOIN profile_pics ON (profile_pics.id = people.pic) 
WHERE username = '".$_GET['u']."' LIMIT 1";
$bloginfoQuery=@mysql_query($bloginfoSQL);
if (!$bloginfoQuery) 
{ 
	die ("error with blog info query. cannot continue".@mysql_error()); 

}
if (@mysql_num_rows($bloginfoQuery)==0) { header("Location: ../index.php"); }
$blog=@mysql_fetch_array($bloginfoQuery);


$gradeQuery = @mysql_query("SELECT * FROM grades");
if (!$gradeQuery) die ("Error with grades query ".@mysql_error());

while ($grade=@mysql_fetch_array($gradeQuery))
{
	$grades[$grade['year']]=$grade['grade'];
	$grades[$grade['year']."p"]=$grade['grade_plural'];
}

if ($_GET['s'])
{
	$skinQuery=@mysql_query("SELECT html, css, name FROM skins WHERE id = '".$_GET['s']."'");	
	if (!$skinQuery) die ('Error with skin query '.@mysql_error());
	if (@mysql_num_rows($skinQuery)<1) die ('Could not find skin');
	$skin = @mysql_fetch_array($skinQuery);
}
else if ($blog['skin'])
{
	$skinQuery=@mysql_query("SELECT html, css, name FROM skins WHERE id = '".$blog['skin']."'");	
	if (!$skinQuery) die ('Error with skin query '.@mysql_error());
	if (@mysql_num_rows($skinQuery)<1) die ('Could not find skin');
	$skin = @mysql_fetch_array($skinQuery);
}

if ($_GET['t']) 
	$templateSQL="SELECT * FROM templates WHERE id ='".$_GET['t']."' LIMIT 1";
else
	$templateSQL="SELECT * FROM templates WHERE id ='".$blog['theme']."' LIMIT 1";
$templateQuery=@mysql_query($templateSQL);
if (!$templateQuery) die ('Error with template query!');
if (@mysql_num_rows($templateQuery)==0) die ('Template  not found!');
$template=@mysql_fetch_array($templateQuery);


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT name, class, username, id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);

}
if ($person['id'] != $blog['id'])
{
    $hits=$blog['visited']+1;
    $hitSQL="UPDATE people SET visited = '".$hits."' WHERE id = '".$blog['id']."'";
    $hitQuery=@mysql_query($hitSQL);
    if (!$hitQuery) die ('error incrementing counter');
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



header("cache-control:private"); 

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE><?php echo $blog['username']; ?> :. Olentangy Life</TITLE>
    
    <?php if ($skin) { echo "<!-- Using skin '".$skin['name']."' -->"; } ?>
    
    <STYLE TYPE="text/css" MEDIA="screen">
    <!--
    
    <?php if ($skin) { echo $skin['css']."\n"; }
    
    else  {
    
    ?>
    body
    {	background:<?php echo $template['bg_color']; ?>; }
    
    .headline
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:30px;
    	color:<?php echo $template['headline_color']; ?>;
    }
    
     .tagline
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:22px;
    	color:<?php echo $template['tagline_color']; ?>;
    }
    
    .name
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:22px;
    	text-transform: uppercase;
    	color:<?php echo $template['name_color']; ?>;
    	margin-bottom:0px;
    	
    }
    
    h1
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:22px;
    	text-transform: uppercase;
    	color:<?php echo $template['name_color']; ?>;
    	margin-bottom:0px;
    	margin-top:0px;
    	
    }
    
    .uname
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:10px;
    	color:<?php echo $template['uname_color']; ?>;
    }
    
    .uname a
    {
    	color:<?php echo $template['uname_color']; ?>
    }
    .grade
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:14px;
    	text-transform: uppercase;
    	color:<?php echo $template['grade_color']; ?>;
    }
    
    .grade a
    {
    	color:<?php echo $template['grade_color']; ?>
    }
    
    
    .olife
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:12px;
    	text-transform: uppercase;
    	color:<?php echo $template['name_color']; ?>;
    }
    
    .pagelinks
    {
    
    font-family:"Arial Black",Arial,sans-serif;
    	font-size:12px;
    	text-transform: uppercase;
    	color:<?php echo $template['name_color']; ?>;
    }
       
    .itemlinks
    {
    	font-family:"Arial Black",Arial, sans-serif;
    	font-size:10px;
    	color:<?php echo $template['grade_color']; ?>;
    	margin-top:0px;
    	text-transform: uppercase;text-transform: uppercase;
    }
    
    .olife a
    {

    	color:<?php echo $template['name_color']; ?>;
    }
    .about
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:14px;
    	color:<?php echo $template['about_color']; ?>;
    }
    
    .act
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:12px;
    	color:<?php echo $template['act_color']; ?>;
    }
    
    p
    {
    	font-family:Arial,sans-serif;
    	font-size:12px;
    	color:<?php echo $template['headline_color']; ?>;
    	margin-top:0px;
    }
    
    .heading
    {
    	font-family:"Arial Black",Arial,sans-serif;
    	font-size:14px;
    	color:<?php echo $template['headline_color']; ?>;
    	margin-bottom:0px;
    }
    
    .date
   	{
   	font-family:"Arial Black",Arial,sans-serif;
    	font-size:14px;
    	color:<?php echo $template['grade_color']; ?>;
    	margin-bottom:0px;
    	text-transform: uppercase;
    }
    
    about a, about a:active, about a:visited;
    {
    	color:<?php echo $template['tagline_color']; ?>;
    }
    
    a
  	{
  		text-decoration:none;
  		color:<?php echo $template['name_color']; ?>;
  	}
  	
  	a:hover
  	{
  		text-decoration:underline;
  	}
  	
  	.posts
  	{
  		border-left: 1px solid <?php echo $template['name_color']; ?>;
  		padding-left:10px;
  	}
  	
  		.profilepic
  	{
  		border:1px solid <?php echo $template['name_color']; ?>;
  		margin:10px;
  		margin-bottom:0px;
  	
  	}
  	
  	<?php } ?>
    -->
    </STYLE>
  </HEAD>
  <BODY>
  <?php 
  
  if ($skin)
  {	// we're using a skin, so disregard anything outside of this if statement
	$masterPage=$skin['html'];
	
	//various onetime things....
		while (strpos($masterPage, "%headline%"))
		{	$masterPage=str_replace("%headline%",$blog['headline'],$masterPage); }
	
		while (strpos($masterPage, "%tagline%"))
		{	$masterPage=str_replace("%tagline%",$blog['tagline'],$masterPage); }

		while (strpos($masterPage, "%username%"))
		{	$masterPage=str_replace("%username%",$blog['username'],$masterPage); }

		while (strpos($masterPage, "%name%"))
		{	$masterPage=str_replace("%name%",$blog['name'],$masterPage); }
	
		while (strpos($masterPage, "%about%"))
		{	$masterPage=str_replace("%about%",$blog['about'],$masterPage); }
		
		
	   while (strpos($masterPage, "%olife_links%"))
		{			
	$linkstr= "<a href='../'>olife home</a> > <a href='../blogs/'>blogs</a> > ";

	  $linkstr=$linkstr."<a href='../people/?c=".$blog['class']."'>";
	  
      if ($blog['class']=='6') $linkstr=$linkstr."seniors";
		if ($blog['class']=='7') $linkstr=$linkstr."juniors";
			if ($blog['class']=='8') $linkstr=$linkstr."sophomores";
				if ($blog['class']=='9') $linkstr=$linkstr."freshmen";
				
	 $linkstr=$linkstr."</a>";
	 	
	 
	   if ($person)
	   {
 			$linkstr=$linkstr." > <a href='../private/'>you</a>";
 		
 		}
 		else
 		{
 			$_SESSION['login_redirect']=$_SERVER['PHP_SELF']."?u=".$blog['username'];
 			$linkstr=$linkstr." > <a href='../login.php'>login</a>";
 		}
 	
 	
 		$masterPage=str_replace("%olife_links%",$linkstr,$masterPage);
 	
 		
 	
 	
 	}

	//slightly more complicated things...
		while (strpos($masterPage, "%aim%"))
		{	$masterPage=str_replace("%aim%","<a href='aim:goim?screenname=".$blog['aim']."'>".$blog['aim']."</a>",$masterPage); }
		
		while (strpos($masterPage, "%aim_osi%"))
		{	
		
		
	

			$url = @fsockopen("big.oscar.aol.com", 80, &$errno, &$errstr, 3);
   			fputs($url, "GET /".$blog['aim']."?on_url=online&off_url=offline HTTP/1.0\n\n");
   			while(!feof($url)){
     	    $feofi++;
     	    $aimpage .= fread($url,256);
     	    if($feofi > 10){
    		            $aimpage = "offline";
      	          break;
     		   }
				}
				fclose($url);
		
			
			if(strstr($aimpage, "online")){
    		 $osi= "<A HREF='aim:goim?screenname=".$blog['aim']."'><IMG SRC='../images/aimonline.gif' align='center' border='0' ALT='online'></A></p>";
   	
			}else{
  	 	     $osi= "<A HREF='aim:goim?screenname=".$blog['aim']."'><IMG SRC='../images/aimoffline.gif' align='center' border='0' ALT='online'></A></p>";

			}

	
	
		
		$masterPage=str_replace("%aim_osi%",$osi,$masterPage); 
		
		}

		while (strpos($masterPage, "%journal_link%"))
		{	$masterPage=str_replace("%journal_link%","<a href='".$blog['journal']."'>my other journal</a>",$masterPage); }

		while (strpos($masterPage, "%profile_link%"))
		{	$masterPage=str_replace("%profile_link%","<a href='../people/profile.php?u=".$blog['username']."'>my profile</a>",$masterPage); }
		
		while (strpos($masterPage, "%albums_link%"))
		{	$masterPage=str_replace("%albums_link%","<a href='../albums/?u=".$blog['username']."'>my albums</a>",$masterPage); }
	
		while (strpos($masterPage, "%message_link%"))
		{	$masterPage=str_replace("%message_link%","<a href='../private/compose.php?to=".$blog['username']."'>send me a message</a>",$masterPage); }


		while (strpos($masterPage, "%back_to_link%"))
		{	$masterPage=str_replace("%back_to_link%","",$masterPage); }


		while (strpos($masterPage, "%class%"))
		{	$masterPage=str_replace("%class%",$grades[$blog['class']],$masterPage); }


		while (strpos($masterPage, "%subscribe_link%"))
		{	
		echo "<!-- entering subscribe loop -->";
			if ($person)
				{  $_SESSION['subscribe_redirect']="index.php?u=".$blog['username'];
		
				      $subscriptionSQL="SELECT * FROM subscriptions WHERE er = '".$person['id']."' AND ee = '".$blog['id']."'";
 				     $subscriptionQuery=@mysql_query($subscriptionSQL);
				      if (!$subscriptionQuery) die ('Error checking for subscription '.@mysql_error());
				      if (@mysql_num_rows($subscriptionQuery) > 0) $un="un";
	
					
					$masterPage=str_replace("%subscribe_link%","<a href='".$un."subscribe.php?u=".$blog['username']."'>".$un."subscribe to me</a>",$masterPage);
	
	
	
	
	
					 }
					 else
					 {
					 	$masterPage=str_replace("%subscribe_link%","",$masterPage);
					 }
		
		}

			 	
		while (strpos($masterPage, "%picture%"))
		{	if ($blog['pic']) $masterPage=str_replace("%picture%","<img class='picture' src='blogpic.php?pic=".$blog['pic']."'>",$masterPage); 
				else $masterPage=str_replace("%picture%","",$masterPage);
		
		}

		while (strpos($masterPage, "%activities%"))
		{
			
				$actSQL="SELECT activities.name AS name, activities.id AS id FROM act_enrollments 
   				LEFT JOIN activities ON (activities.id = act_enrollments.activity)
				WHERE (act_enrollments.person='".$blog['id']."') ORDER BY name ASC";
				$actQuery=@mysql_query($actSQL);
				if (!$actQuery) die ('Error with activities query '.@mysql_error());
				if (mysql_num_rows($actQuery)<1) $activities="no activities";
			while ($activity=@mysql_fetch_array($actQuery))
			{
				$activities=$activities."<a href='../people/?a=".$activity['id']."'>".$activity['name']."</a><Br/>";
			}
		
		
	
				$masterPage=str_replace("%activities%",$activities,$masterPage); 
		}

		//MUCH MORE COMPLICATED STUFF
		
		$commentsBegin=strpos($masterPage,"%comments%");  //remove comments code... this is the main page.
		$commentsEnd=strpos($masterPage,"%end_comments%");
		$masterComments=substr($masterPage,$commentsBegin,$commentsEnd-$commentsBegin+14);
		$masterPage=str_replace("%comment_form%","",$masterPage);
		
		$masterPage=str_replace($masterComments,"",$masterPage);
		
		$postsBegin=strpos($masterPage,"%posts%");
		$postsEnd=strpos($masterPage,"%end_posts%");
		$masterPosts=substr($masterPage,$postsBegin,$postsEnd-$postsBegin+11);
		$strippedPosts=str_replace("%posts%","",$masterPosts);
		$strippedPosts=str_replace("%end_posts%","",$strippedPosts);
		$finalPosts="";
		
		$postsSQL="SELECT * FROM posts WHERE person = '".$blog['id']."' AND deleted ='0' ORDER BY datetime DESC LIMIT $from, $max_results";
		$postsQuery=@mysql_query($postsSQL);
		if (!$postsQuery) die ('Error with posts query! '.@mysql_error());
	
		while($post=@mysql_fetch_array($postsQuery))
		{
			
			if ($post['deleted']==1) 
			{
				
			}
			else 
			{
				$currentPost=$strippedPosts;
				
				while (strpos($currentPost, "%post_heading%"))
				{	$currentPost=str_replace("%post_heading%",$post['heading'],$currentPost); }
	
				while (strpos($currentPost, "%post_date%"))
				{	$currentPost=str_replace("%post_date%",date('l n/j g:iA',($post['datetime']+(60*60*3))),$currentPost); }
	
				while (strpos($currentPost, "%post_body%"))
				{	
						$theString = str_replace("\n", "<br />", $post['body']."<br/>");
						$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
						$smileysQuery=@mysql_query($smileysSQL);
						if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
		
	
						while ($smiley=@mysql_fetch_array($smileysQuery))
						{
							$theString=str_replace($smiley['ocode'],"<img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'>",$theString);
	
						}
							if ($post['picture']!=0)
	{
		$picture=one_result_query("SELECT album_pics.file AS file, album_pics.description AS description FROM album_pairs
			LEFT JOIN album_pics ON (album_pics.id = album_pairs.album_pic) WHERE album_pairs.id = '".$post['picture']."'","picture query");
		$theString="<a href='../albums/viewpicture.php?id=".$post['picture']."'><img src='../album_pics/blog".$picture['file']."' align='left' style='border:none; margin-right:10px; margin-bottom:10px;'></a>".$theString;
	}
	
							$currentPost=str_replace("%post_body%",$theString."<br clear=left>",$currentPost); 
				
				}
		
				while (strpos($currentPost, "%post_comments%"))
				{	
					if ($blog['allow_comments']!=0)
					{
					$commentString= "<a href='post.php?id=".$post['id']."'>";
					$commentSQL="SELECT COUNT(*) as Num FROM comments WHERE postid='".$post['id']."'";
					$commentQuery=@mysql_query($commentSQL);
					if (!$commentQuery) die ('Error with comment query '.@mysql_error());
					$comment=@mysql_fetch_array($commentQuery);
					$commentString=$commentString.$comment['Num'];
					
					if ($numcomments > 1 || $numcomments == 0)
						$commentString=$commentString." comments";
					else
						$commentString=$commentString." comment";
					$commentString=$commentString."</a>";
					
					$currentPost=str_replace("%post_comments%",$commentString,$currentPost);
					}
					else
					$currentPost=str_replace("%post_comments%","",$currentPost);
					
					
				}
			
			$finalPosts=$finalPosts.$currentPost;
			}
		}
		
		
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM posts WHERE person='".$blog['id']."'"),0); 

// Figure out the total number of pages. Always round up using ceil() 
$total_pages = ceil($total_results / $max_results); 


// Build Page Number Hyperlinks 
$finalPosts=$finalPosts."<center>"; 
$finalPosts=$finalPosts."<div class='pagelinks'>";
// Build Previous Link 
if($page > 1){ 
$prev = ($page - 1); 
$finalPosts=$finalPosts."<a href=\"".$_SERVER['PHP_SELF']."?u=".$blog['username']."&page=$prev\">Previous</a> "; 
} 



// Build Next Link 
if($page < $total_pages){ 
$next = ($page + 1); 
$finalPosts=$finalPosts."<a href=\"".$_SERVER['PHP_SELF']."?u=".$blog['username']."&page=$next\">Next</a>"; 
} 

$finalPosts=$finalPosts."</div>";
$finalPosts=$finalPosts."</center>"; 


	

		$masterPage=str_replace($masterPosts,$finalPosts,$masterPage);




		

 	
	echo $masterPage; //shove it

	} //END SKINS!
	else
	{
	?>
   <table width=100%>
   <tr><td class="headline" ><?php echo $blog['headline']; ?></td>
   
   <td class="olife" align=right>
   
   <?php
   
  
      echo "<a href='../'>olife home</a> > <a href='../blogs/'>blogs</a> > ";

	  echo "<a href='../people/?c=".$blog['class']."'>";
	  
      if ($blog['class']=='6') echo "seniors";
		if ($blog['class']=='7') echo "juniors";
			if ($blog['class']=='8') echo "sophomores";
				if ($blog['class']=='9') echo "freshmen";
				
	 echo "</a>";
	 
	 
   if ($person)
   {
 		echo " > <a href='../private/'>you</a><br/>";
 		
 	}
 	else
 	{
 			$_SESSION['login_redirect']=$_SERVER['PHP_SELF']."?u=".$blog['username'];
 		echo " > <a href='../login.php'>login</a>";
 	}
 	?>

   
   
   </td></tr></table>
   <table width=100%>
   <td class="tagline" align=right width=100%><?php echo $blog['tagline']; ?></td>
   </table>

	<table width=100% cellspacing=10>
	<td width=20% valign=top>
	<font class="name"><?php echo $blog['name']; ?></font><br/>
	<font class="grade"><?php 
	
	if ($blog['class']=='6') echo "senior";
		if ($blog['class']=='7') echo "junior";
			if ($blog['class']=='8') echo "sophomore";
				if ($blog['class']=='9') echo "freshman";
	
	
	?>
	
	</font><br/>
	<div class="uname" align="center"><?php echo $blog['username']; ?><br/></div>
	<div class="about" align="center">
	<a href="../people/profile.php?u=<?php echo $blog['username']; ?>">view my profile</a><Br/>
	<?php if ($person)
	{  $_SESSION['subscribe_redirect']="index.php?u=".$blog['username'];
		
      $subscriptionSQL="SELECT * FROM subscriptions WHERE er = '".$person['id']."' AND ee = '".$blog['id']."'";
      $subscriptionQuery=@mysql_query($subscriptionSQL);
      if (!$subscriptionQuery) die ('Error checking for subscription '.@mysql_error());
      if (@mysql_num_rows($subscriptionQuery) > 0) $un="un";
	
		echo "<a href='../private/compose.php?to=".$blog['username']."'>send me a message</a>";
		
	?> <a href='<?php echo $un; ?>subscribe.php?u=<?php echo $blog['username']; ?>'><?php echo $un; ?>subscribe to me</a> 
	
	
	
	
	
	<?php } ?>
	
	
	
	</div>
	<?php 
if ($blog['pic'])
{
//	echo $width;
//	echo " ".$height;
	
echo "<div align='center'><img class='profilepic' src='blogpic.php?pic=".$blog['pic']."'/></div>";
	
   // echo "<img class='profilepic' src='../pics/".$blog['pic']."'>";
    
	
 }	?><br/>
	
	
	<div class="about" align=center>
	<?php if ($blog['aim']) 
	{
	echo "AIM: <a href='aim:goim?screenname=".$blog['aim']."'>".$blog['aim']."</a><br/>"; 
	
	// Connect to AOL server
		$url = @fsockopen("big.oscar.aol.com", 80, &$errno, &$errstr, 3);
   		fputs($url, "GET /".$blog['aim']."?on_url=online&off_url=offline HTTP/1.0\n\n");
   		while(!feof($url)){
        $feofi++;
        $page .= fread($url,256);
        if($feofi > 10){
                $page = "offline";
                break;
        }
		}
		fclose($url);
		
		// determine online status 
if(strstr($page, "online")){
       echo "<A HREF='aim:goim?screenname=".$profile['aim']."'><IMG SRC='../images/aimonline.gif' align='center' border='0' ALT='online'></A></p>";
   	
}else{
        echo "<A HREF='aim:goim?screenname=".$profile['aim']."'><IMG SRC='../images/aimoffline.gif' align='center' border='0' ALT='online'></A></p>";

}

	
	}?>

	<?php if ($blog['journal']) echo "<a href='".$blog['journal']."'>my other journal</a>";?><br/><Br/>	
	<?php echo str_replace("\n", "<br />", $blog['about']."<br/>") ?></div><br/>
	
	</div><br/>
		
	<div align="center" class="grade">Activities</div>
	<div align="center" class="act">
	<?php 
			$actSQL="SELECT activities.name AS name, activities.id AS id FROM act_enrollments 
   	LEFT JOIN activities ON (activities.id = act_enrollments.activity)
   	WHERE (act_enrollments.person='".$blog['id']."') ORDER BY name ASC";
		$actQuery=@mysql_query($actSQL);
		if (!$actQuery) die ('Error with activities query '.@mysql_error());
		if (mysql_num_rows($actQuery)<1) echo "no activities";
		else
		{
			while ($activity=@mysql_fetch_array($actQuery))
			{
				echo "<a href='../people/?a=".$activity['id']."'>".$activity['name']."</a><Br/>";
			}
		
		}
		
	
	?>	
	</div>
	</td>
	
	<td width=80% valign=top class="posts";>
	<h1>POSTS</h1>
	
	<?php
	
		$postsSQL="SELECT * FROM posts WHERE person = '".$blog['id']."' AND deleted ='0' ORDER BY datetime DESC LIMIT $from, $max_results";
		$postsQuery=@mysql_query($postsSQL);
		if (!$postsQuery) die ('Error with posts query! '.@mysql_error());
	
		while($post=@mysql_fetch_array($postsQuery))
		{
			
			if ($post['deleted']==1) 
			{
			
			}
			else 
			{
			
						echo "<font class='heading'>".$post['heading']." - </font><font class='date'>".date('l n/j g:iA',($post['datetime']+(60*60*3)))."</font>";
						echo "<p>";
						$theString = str_replace("\n", "<br />", $post['body']."<br/>");
	$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
	$smileysQuery=@mysql_query($smileysSQL);
	if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
	
	
	while ($smiley=@mysql_fetch_array($smileysQuery))
	{
		$theString=str_replace($smiley['ocode'],"<img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'>",$theString);
		
	}
	
	if ($post['picture']!=0)
	{
		$picture=one_result_query("SELECT album_pics.file AS file, album_pics.description AS description FROM album_pairs
			LEFT JOIN album_pics ON (album_pics.id = album_pairs.album_pic) WHERE album_pairs.id = '".$post['picture']."'","picture query");
		$theString="<a href='../albums/viewpicture.php?id=".$post['picture']."'><img src='../album_pics/blog".$picture['file']."' align='left' style='border:none; margin-right:10px; margin-bottom:10px;'></a>".$theString;
	}
	echo $theString."<br clear='left'>";
	
		
				if ($blog['allow_comments']!=0)
				{
					echo "<font class='itemlinks'>";
					echo "<a href='post.php?id=".$post['id']."'>";
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
						
					echo "</font>";
				 }
				 
				 echo "</p>";
			
			}
		}
			
		
		// Figure out the total number of results in DB: (TAKEN FROM PHPFREAKS.COM)
		
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM posts WHERE person='".$blog['id']."' AND deleted !='1'"),0); 

// Figure out the total number of pages. Always round up using ceil() 
$total_pages = ceil($total_results / $max_results); 

// Build Page Number Hyperlinks 
echo "<center>"; 
echo "<div class='pagelinks'>";
// Build Previous Link 
if($page > 1){ 
$prev = ($page - 1); 
echo "<a href=\"".$_SERVER['PHP_SELF']."?u=".$blog['username']."&page=$prev\">Previous</a> "; 
} 



// Build Next Link 
if($page < $total_pages){ 
$next = ($page + 1); 
echo "<a href=\"".$_SERVER['PHP_SELF']."?u=".$blog['username']."&page=$next\">Next</a>"; 
} 

echo "</div>";
echo "</center>"; 
	?>
		
		</td>
	</table>
  
   <?php } ?>
  </BODY>
</HTML>