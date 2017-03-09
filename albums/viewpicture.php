<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');
$section="albums";


if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT * FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
}



if (!$_GET['id']) {	header("location: index.php"); }
else
{
	
	$picture=one_result_query("SELECT album_pairs.date AS date, albums.access AS access, albums.creator AS creator, albums.id AS album_id, albums.name AS album_name, album_pics.file AS file, album_pics.name AS name, album_pics.description AS description, album_pics.id AS id, albums.creator AS album_person FROM album_pairs LEFT JOIN albums ON (albums.id=album_pairs.album) LEFT JOIN album_pics ON (album_pics.id=album_pairs.album_pic) WHERE album_pairs.id ='".$_GET['id']."'","picture info");
	if (!$picture['creator']) die ('Picture not found');
	
	
	//check album access!!!! added 11/27/05
	if ($picture['access']==3 && $person['id']!=$picture['creator'])
		die ('You do not have access to this album.');
	if ($picture['access']==2)
	{
		if (!$person) die ('You do not have access to this album. Try logging in.');
		
		$subscription=one_result_query("SELECT * FROM subscriptions WHERE er='".$userinfo['id']."' AND ee='".$person['id']."'","subscription test query");
			if (!$subscription['id']) die ('You do not have access to this album.');
				
		
	}
	if ($picture['access']==1)
	{
		if (!$person) die ('You do not have access to this album. Try logging in.');	
	}
	
	
	$userinfo=one_result_query("SELECT people.id AS id, people.about AS about, people.name AS name, people.username AS username, profile_pics.filename AS pic FROM people LEFT JOIN profile_pics ON (profile_pics.id = people.pic) WHERE people.id='".$picture['creator']."'","user info");
	if (!$userinfo['name']) die('User not found');
	
	//if ($picture['album_person']!=$person['id']) //not your album
	//	die ('<p><b>You cannot edit this picture (in this album).</b></p>');
		
}

	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>View Picture :. Olentangy Life</TITLE>
    <link rel="stylesheet" media="screen" href="../olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="../images/olentangy_life.gif"></div>
  
   <div id="tagline">the best olentangy student community ever.</div>
   <div id="bar"> </div>
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
 
   
   <form method="post" action="logincheck.php">      
    <p>
		
		<b>USER:</b>
		
		<input type="text" name="username" value="username" size=8>
		
		<b class="gold">PW:</b>
		
		<input type="password" name="password" size=8>
		<?php $_SESSION['login_redirect']="albums/viewpicture.php?id=".$picture['id']; ?>
		 <input class="submit" type="image" name="login" src="../images/login.gif"> <a href="register.php"class="submit"><img src="../images/register.gif" alt="register"></a>
		 
    </p>
  </form>
   
   <?php 
   
   }
   
   ?>
   
   
   </div>
<?php include ('../master_nav.php'); ?>

   <div id="leftbar" align='right'>
   <?php
  	if ($userinfo['pic'])
  		echo "<img alt='".$userinfo['username']."' src='../blog/blogpic.php?pic=".$userinfo['pic']."' align='right' style='border:0px;'>";
   
  	?>
  	<br clear='right'>
  	<p><b><?php echo $userinfo['username']; ?></b></p>
  	<p><a class='blue' href='../people/profile.php?u=<?php echo $userinfo['username']; ?>'>profile</a><br/>
  	<a class='blue' href='../blog/?u=<?php echo $userinfo['username']; ?>'>blog</a><br/></p>
  	<p><?php echo $userinfo['about']; ?></p>
  	
  	<a href="http://marykay.com/llillemoen"><img src="../ad.jpg" style="border:0px;"></a><br/>
   </div>
   
   
   <div id="mainnoright">
	<h1>VIEW PICTURE</h1>
	<h2><?php echo $picture['name']; ?></h2>
		
		
	<p class='soft'>added <?php echo date('m/d/y',$picture['date']); ?></p>
	<center>
	<?php //figure out what next picture is
			$nextSQL="SELECT id FROM album_pairs WHERE album='".$picture['album_id']."' AND date < '".$picture['date']."' ORDER BY date DESC LIMIT 1"	;
			$nextpic=one_result_query($nextSQL,"next pic query");
			//die ($nextSQL);
			$prevpic=one_result_query("SELECT id FROM album_pairs WHERE album='".$picture['album_id']."' AND date > '".$picture['date']."' ORDER BY date ASC LIMIT 1","prev pic query");
			echo "<p><b>";
			if ($prevpic['id'])
				echo "<a href='viewpicture.php?id=".$prevpic['id']."' class='gold'>previous</a>";
			if ($nextpic['id'])
				echo " <a href='viewpicture.php?id=".$nextpic['id']."' class='gold'>next</a>";
			
			echo "<br/><br/>"
		
		?>
	
	
	
	<b><a class='blue' href='/albums/?album=<?php echo $picture['album_id']; ?>'>back to "<?php echo $picture['album_name'];?>"</a></b></p>
	
	
	</center>
	

		
		<center><img src="../album_pics<?php echo $picture['file']; ?>" alt="<?php echo $picture['name']; ?>" style="margin-right:15px" align="center"></center><br/>
		<center><p><?php echo $picture['description']; ?><br/></p></center>
		
		
		<h1>COMMENTS</h1>
		
		<?php
	
			$commentSQL="SELECT people.name AS commenter_name, profile_pics.filename AS pic, people.username AS commenter_username, picture_comments.comment, picture_comments.datetime FROM picture_comments 
			LEFT JOIN people ON (picture_comments.person = people.id) 
			LEFT JOIN profile_pics ON (people.pic = profile_pics.id) 
			WHERE (picture_comments.postid='".$_GET['id']."') ORDER BY datetime ASC";
		
			$commentQuery=@mysql_query($commentSQL);
			if (!$commentQuery) die ('Error with comment query '.@mysql_error());
			$numComments=@mysql_num_rows($commentQuery);
			
		
			while ($comment=@mysql_fetch_array($commentQuery))
			{
			
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
	
	echo $theString;
	
				echo "<font class='date'><a class='gold' href='../people/profile.php?u=".$comment['commenter_username']."'>".$comment['commenter_name']." (".$comment['commenter_username'].")</a> <b>".date('l n/j g:iA',($comment['datetime']+(60*60*3)))."</b></font>";
				echo "</td></table>";
			}
			echo "<br/>";
		
	if (!$person)
			{	
				$_SESSION['login_redirect']="albums/viewpicture.php?id=".$_GET['id']; 
				echo "<p><a class='blue' href='../login.php'>login to post a comment</a></p>";
			}
			else
			{
			?>
				<h1>POST A COMMENT</h1>
				<form method="post" action="postcomment.php">
			
					<textarea rows="5" columns="10" name="comment">Type your comment here.</textarea><br/><Br/>
				
				
				<button type="submit">Comment</button>
				
				<input type="hidden" name="postid" value="<?php echo $_GET['id']; ?>">
		
				</form>
			<?php
			}
			?>
	
	</div>
	

  
   
  </BODY>
</HTML>