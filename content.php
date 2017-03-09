<?php

session_start();
header("cache-control:private");

include ('dbconnect.php');
if ($_SERVER['HTTP_REMOTE_ADDR'] == "156.63.253.3")
{	$message="<p><b>You are viewing this from school.  I have nothing to do with any trouble this may get you into.</b></p>"; }


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT name, class, username FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
	
}
	
$content_id=$_GET['id'];
if(!$content_id) Header ("location:index.php");

$contentSQL="SELECT content.title AS title, content.content AS content, content.datetime AS datetime, people.username AS username, people.name AS name, people.class AS class FROM content LEFT JOIN people ON people.id = content.creator WHERE content.id ='".$content_id."'";
$contentQuery=@mysql_query($contentSQL);
if (!$contentQuery) die ('Error with content query '.@mysql_error());
$content=@mysql_fetch_array($contentQuery);


	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>Olentangy Life :. <?php echo $content['title']; ?></TITLE>
    <link rel="stylesheet" media="screen" href="olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="images/olentangy_life.gif" ALT='Olentangy Life'></div>
  
   <div id="tagline">the best olentangy student community ever.</div>
   <div id="bar"> </div>
   <div id="login">
   
   <?php
   
   if ($person)
   {
      echo "<p><b>LOGGED IN AS ".$person['name']." (".$person['username'].") - <a href='private/'>YOU</a> - <a href='blog/?u=".$person['username']."'>YOUR BLOG</a> - <a href='logout.php'>LOGOUT</a></b></p>";
    }
    else
    {
    ?>
 
   
   <form method="post" action="logincheck.php">      
    <p>
		
		<b>USER:</b>
		
		<input type="text" name="username" value="username" size=8>
		
		<b class="gold">PW:</b>
		
		<input type="password" name="password" size=8>
		<?php $_SESSION['login_redirect']="content.php?id=".$content_id; ?>
		 <input class="submit" type="image" name="login" src="images/login.gif"> <a href="register.php"class="submit"><img src="images/register.gif" alt="register"></a>
		 
    </p>
  </form>
   
   <?php 
   
   }
   
   ?>
   
   
   </div>
   <div id="nav"><a href='index.php'>HOME</a> <a href='about.php'>ABOUT</a> <a href="people/">PEOPLE</a> <a href="blogs/">BLOGS</a></div>
   
   <div id="leftbar" align="right">

   <p><b>By <a class='blue' href="people/profile.php?u=<?php echo $content['username']; ?>"><?php echo $content['name']; ?></a></b></p>
   <p><i>All articles on Olentangy Life reflect the opinions of their authors only.  While they (unlike blog posts) may be edited for content/suitability, they are still the sole responsibility of the author.</i></p>
   
<script type="text/javascript"><!--
google_ad_client = "pub-3878855217301525";
google_ad_width = 120;
google_ad_height = 240;
google_ad_format = "120x240_as";
google_ad_type = "text";
google_ad_channel ="";
google_color_border = "CCCCCC";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_url = "666666";
google_color_text = "333333";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

   </div>
   
   
   
   <div id="mainnoright">
    <h1><?php echo $content['title']; ?></h1>
    <h2><?php echo date("F jS Y",$content['datetime']); ?></h2><br/>
	<p><?php echo str_replace("\n","<br/>",$content['content']); ?></p>
	
	<?php
	
	echo "<h1>COMMENTS</h1><br/>";
			$commentSQL="SELECT people.name AS commenter_name, profile_pics.filename AS pic, people.username AS commenter_username, content_comments.comment, content_comments.datetime FROM content_comments 
			LEFT JOIN people ON (content_comments.person = people.id) 
			LEFT JOIN profile_pics ON (people.pic = profile_pics.id) 
			WHERE (content_comments.postid='".$content_id."') ORDER BY datetime ASC";
		
			$commentQuery=@mysql_query($commentSQL);
			if (!$commentQuery) die ('Error with comment query '.@mysql_error());
			$numComments=@mysql_num_rows($commentQuery);
			
		
			while ($comment=@mysql_fetch_array($commentQuery))
			{
			
				echo "<table cellpadding=5>";
				echo "<td>";
				if ($comment['pic']) echo "<tr><td valign=top width=70><a href='people/profile.php?u=".$comment['commenter_username']."'><img style='border:none; margin-bottom:10px;' src='people/userpic.php?pic=".$comment['pic']."' align='left'></a><Br/>";
   		
   		
   			else echo "<tr><td width=70 valign=top><a href='people/profile.php?u=".$comment['commenter_username']."'><img style='border:none; margin-bottom:10px;' src='images/no_pic.gif' align='left'></a>";
   		
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
	
	echo $theString;
	
				echo "<font class='date'><a class='gold' href='people/profile.php?u=".$comment['commenter_username']."'>".$comment['commenter_name']." (".$comment['commenter_username'].")</a> <b>".date('l n/j g:iA',($comment['datetime']+(60*60*3)))."</b></font>";
				echo "</td></table>";
			}
			echo "<br/>";
		
	if (!$person)
			{	
				$_SESSION['login_redirect']="content.php?id=".$content_id; 
				echo "<p><a class='blue' href='../login.php'>login to post a comment</a></p>";
			}
			else
			{
			?>
				<h1>POST A COMMENT</h1>
				<form method="post" action="postcomment.php">
			
					<textarea rows="5" columns="10" name="comment">Type your comment here.</textarea><br/><Br/>
				
				
				<button type="submit">Comment</button>
				
				<input type="hidden" name="postid" value="<?php echo $content_id; ?>">
		
				</form>
			<?php
			}
			?>

</div>


   
  </BODY>
</HTML>