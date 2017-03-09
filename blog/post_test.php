<?php

session_start();
include ('../dbconnect.php');
if (!$_GET['id']) die ("no id"); //header("Location: ../index.php");

$postinfoSQL="SELECT * FROM posts WHERE id =".$_GET['id'];


$postinfoQuery=@mysql_query($postinfoSQL);
if (!$postinfoQuery) die ('Error with post query '.@mysql_error());
if (@mysql_num_rows($postinfoQuery)==0) die ('Post not found!');
$post=@mysql_fetch_array($postinfoQuery);

if ($post['deleted']=='1') die ('Post not found!');

$bloginfoSQL="SELECT name, username, skin, class, aim, email, sex, people.id, headline, tagline, about, journal, profile_pics.filename AS pic, profile_pics.filesize AS pic_size, profile_pics.filetype AS pic_type, theme FROM people
LEFT JOIN profile_pics ON (profile_pics.id = people.pic) 
WHERE people.id = '".$post['person']."' LIMIT 1";
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

if ($blog['skin'])
{
	$skinQuery=@mysql_query("SELECT html, css, name FROM skins WHERE id = '".$blog['skin']."'");	
	if (!$skinQuery) die ('Error with skin query '.@mysql_error());
	if (@mysql_num_rows($skinQuery)<1) die ('Could not find skin');
	$skin = @mysql_fetch_array($skinQuery);
}

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

header("cache-control:private"); 

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE><?php echo $post['heading']; ?> :. Olentangy Life</TITLE>
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
	
		while (strpos($masterPage, "%class%"))
		{	$masterPage=str_replace("%class%",$grades[$blog['class']],$masterPage); }

		while (strpos($masterPage, "%subscribe_link%"))
		{	
			if ($person)
				{  $_SESSION['subscribe_redirect']="index.php?u=".$blog['username'];
		
				      $subscriptionSQL="SELECT * FROM subscriptions WHERE er = '".$person['id']."' AND ee = '".$blog['id']."'";
 				     $subscriptionQuery=@mysql_query($subscriptionSQL);
				      if (!$subscriptionQuery) die ('Error checking for subscription '.@mysql_error());
				      if (@mysql_num_rows($subscriptionQuery) > 0) $un="un";
	
					?> <a href='<?php echo $un; ?>subscribe.php?u=<?php echo $blog['username']; ?>'><?php echo $un; ?>subscribe to me</a> 
	
	
	
	
	
					<?php }
		
		}

		while (strpos($masterPage, "%picture%"))
		{	$masterPage=str_replace("%picture%","<img class='picture' src='blogpic.php?pic=".$blog['pic']."'>",$masterPage); }

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
		
		$postsBegin=strpos($masterPage,"%posts%");
		$postsEnd=strpos($masterPage,"%end_posts%");
		$masterPosts=substr($masterPage,$postsBegin,$postsEnd);
		$strippedPosts=str_replace("%posts%","",$masterPosts);
		$strippedPosts=str_replace("%end_posts%","",$strippedPosts);
		
		
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
							$currentPost=str_replace("%post_body%",$theString."<Br clear='left'>",$currentPost); 
				
						}
		
			
			$finalPosts=$finalPosts.$currentPost;
			$finalPosts=str_replace("%post_comments%","",$finalPosts);
			$finalPosts = "<center><p><a href='index.php?u=".$blog['username']."'>back to posts</a></p><br/><br/></center>".$finalPosts;
			$masterPage=str_replace($masterPosts,$finalPosts,$masterPage);
			$finalPosts="";
		
		$postsBegin=strpos($masterPage,"%comments%");
		$postsEnd=strpos($masterPage,"%end_comments%");
		$masterPosts=substr($masterPage,$postsBegin,$postsEnd);
		$strippedPosts=str_replace("%comments%","",$masterPosts);
		$strippedPosts=str_replace("%end_comments%","",$strippedPosts);
		
		
		$commentSQL="SELECT people.name AS commenter_name, profile_pics.filename AS pic, people.username AS commenter_username, comments.comment, comments.datetime FROM comments 
			LEFT JOIN people ON (comments.person = people.id) 
			LEFT JOIN profile_pics ON (people.pic = profile_pics.id) 
			WHERE (comments.postid='".$post['id']."') ORDER BY datetime ASC";
		
			$commentQuery=@mysql_query($commentSQL);
			if (!$commentQuery) die ('Error with comment query '.@mysql_error());
			$numComments=@mysql_num_rows($commentQuery);
			
		
		while($post=@mysql_fetch_array($commentQuery))
		{
			
			
				$currentPost=$strippedPosts;
				
				while (strpos($currentPost, "%comment_pic%"))
				{	$currentPost=str_replace("%comment_pic%","<a href='../people/profile.php?u=".$post['commenter_username']."'><img style='border:0px;'src='../people/userpic.php?pic=".$post['pic']."'></a>",$currentPost); }
	
				while (strpos($currentPost, "%comment_name%"))
				{	$currentPost=str_replace("%comment_name%",$post['commenter_name'],$currentPost); }

				while (strpos($currentPost, "%comment_username%"))
				{	$currentPost=str_replace("%comment_username%",$post['commenter_username'],$currentPost); }
	
				while (strpos($currentPost, "%comment_date%"))
				{	$currentPost=str_replace("%comment_date%",date('l n/j g:iA',($post['datetime']+(60*60*3))),$currentPost); }
	
				while (strpos($currentPost, "%comment_body%"))
				{	
						$theString = str_replace("\n", "<br />", $post['comment']."<br/>");
						$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
						$smileysQuery=@mysql_query($smileysSQL);
						if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
		
	
						while ($smiley=@mysql_fetch_array($smileysQuery))
						{
							$theString=str_replace($smiley['ocode'],"<img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'>",$theString);
	
						}
							$currentPost=str_replace("%comment_body%",$theString,$currentPost); 
				
						
		

				}
			
			$finalPosts=$finalPosts.$currentPost;
			
		}
		
	
	$masterPage=str_replace($masterPosts,$finalPosts,$masterPage);
	
	echo "<center><p><a href='../'>olife home</a> > <a href='../blogs/'>blogs</a> > ";

	  echo "<a href='../people/?c=".$blog['class']."'>";
	  
      if ($blog['class']=='6') echo "seniors";
		if ($blog['class']=='7') echo "juniors";
			if ($blog['class']=='8') echo "sophomores";
				if ($blog['class']=='9') echo "freshmen";
				
	 echo "</a>";
	 
	 
   if ($person)
   {
 		echo " > <a href='../private/'>you</a><br/></center></p>";
 		
 	}
 	else
 	{
 			$_SESSION['login_redirect']=$_SERVER['PHP_SELF']."?u=".$blog['username'];
 		echo " > <a href='../login.php'>login</a></centeR></p>";
 	}
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
 		echo " > <a href='../private/'>you</a>";
 		
 	}
 	else
 	{
 		$_SESSION['login_redirect']=$_SERVER['PHP_SELF']."?id=".$post['id'];
 		echo " > <a href='../login.php'>login</a>";

 	}
 	?>

   
   
   </td></tr></table>
   <table width=100%>
   <td class="tagline" align=right><?php echo $blog['tagline']; ?></td></tr>
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
	
	?> <a href='<?php echo $un; ?>subscribe.php?u=<?php echo $blog['username']; ?>'><?php echo $un; ?>subscribe to me</a> 
	
	
	
	
	
	<?php } ?>
	
	
	
	</div></div>
<?php 
if ($blog['pic'])
{
//	echo $width;
//	echo " ".$height;
	
echo "<div align='center'><img class='profilepic' src='blogpic.php?pic=".$blog['pic']."'/></div>";
	
   // echo "<img class='profilepic' src='../pics/".$blog['pic']."'>";
    
	
 }	?><br/>
	
		
	<div class="about" align=center>
	<?php if ($blog['aim']) echo "AIM: <a href='aim:goim?screenname=".$blog['aim']."'>".$blog['aim']."</a><br/>"; ?>

	<?php if ($blog['journal']) echo "<a href='".$blog['journal']."'>my other journal</a>";?><br/><Br/>	
	
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
	
	<td width=80% valign=top class="posts">

	
	<?php
	
		    echo "<table width=100%><td>";
			echo "<h1>".$post['heading']."</h1><font class='date'>".date('l n/j g:iA',($post['datetime']+(60*60*3)))."</font></td>";
			echo "<td align=right><a class='act' href='index.php?u=".$blog['username']."'>back to posts</a></td></table>";
			echo "<p>";
			$theString=str_replace("\n", "<br />", $post['body']."<br/><br/>");
			$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
	$smileysQuery=@mysql_query($smileysSQL);
	if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
	
	
	while ($smiley=@mysql_fetch_array($smileysQuery))
	{
		$theString=str_replace($smiley['ocode'],"<img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'>",$theString);
		
	}
	
	echo $theString;
	
		
			echo "<h1>Comments</h1><br/>";
			$commentSQL="SELECT people.name AS commenter_name, profile_pics.filename AS pic, people.username AS commenter_username, comments.comment, comments.datetime FROM comments 
			LEFT JOIN people ON (comments.person = people.id) 
			LEFT JOIN profile_pics ON (people.pic = profile_pics.id) 
			WHERE (comments.postid='".$post['id']."') ORDER BY datetime ASC";
		
			$commentQuery=@mysql_query($commentSQL);
			if (!$commentQuery) die ('Error with comment query '.@mysql_error());
			$numComments=@mysql_num_rows($commentQuery);
			
			while ($comment=@mysql_fetch_array($commentQuery))
			{
				echo "<div class='box'>";
				echo "<table cellpadding=5>";
				echo "<td>";
				if ($comment['pic']) echo "<tr><td valign=top width=70><a href='../people/profile.php?u=".$comment['commenter_username']."'><img style='border:none; margin-bottom:10px;' src='../people/userpic.php?pic=".$comment['pic']."' align='left'></a><Br/>";
   		
   		
   			else echo "<tr><td width=70 valign=top><a href='../people/profile.php?u=".$comment['commenter_username']."'><img style='border:none; margin-bottom:10px;' src='../images/no_pic.gif' align='left'></a>";
   		
				echo "</td>";
				echo "<td>";
				echo "<p>";
				$theString=str_replace("\n", "<br />", $comment['comment'])."</p>";

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
	
				echo "<font class='date'><a href='index.php?u=".$comment['commenter_username']."'>".$comment['commenter_name']." (".$comment['commenter_username'].")</a> <b>".date('l n/j g:iA',($comment['datetime']+(60*60*3)))."</b></font>";
				echo "</td></table>";
				echo "</div>";
			}
			echo "<br/>";
			if (!$person)
			{	
				
				echo "<p><a href='../login.php'>login to post a comment</a></p>";
			}
			else
			{
			?>
				<h1>POST A COMMENT</h1>
				<form method="post" action="postcomment.php">
				<table cellspacing=5>
				<tr>
					<td valign=top><p><b>Your Comment</b></p></td>
					<td valign=top><textarea rows="5" columns="10" name="comment">Type your comment here.</textarea></td>
				</tr>
				<tr><td></td>
				<td><button type="submit">Comment</button></td></tr>
				
				
				</table>
				<input type="hidden" name="postid" value="<?php echo $post['id']; ?>">
		
				</form>
			<?php
			}
			?>
		
		</td>
	</table>
  
<?php } ?>
  </BODY>
</HTML>