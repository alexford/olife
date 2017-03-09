<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');

if (!$_GET['u']) header ("Location:index.php");

$profileSQL="SELECT people.name AS name, people.id AS id, people.username AS username, people.profile AS profile, people.aim AS aim, people.sex AS sex, people.lastupdate AS lastupdate, people.class AS class, people.joindate AS joindate, profile_pics.filename AS pic FROM people LEFT JOIN profile_pics ON (profile_pics.id = people.pic) WHERE people.username='".$_GET['u']."' LIMIT 1";
$profileQuery=@mysql_query($profileSQL);
if (!$profileQuery) die ('error with profile query! '.@mysql_error());
if (@mysql_num_rows($profileQuery)<1) header ("Location:index.php");
$profile=@mysql_fetch_array($profileQuery);

$postsSQL="SELECT heading, datetime,id FROM posts WHERE person='".$profile['id']."' AND deleted !='1' ORDER BY datetime DESC";
$postsQuery=@mysql_query($postsSQL);
if (!$postsQuery) die ('error with posts query '.@mysql_error());
$num_posts=@mysql_num_rows($postsQuery);

$commentsSQL="SELECT comment, datetime, postid FROM comments WHERE person='".$profile['id']."'";
$commentsQuery=@mysql_query($commentsSQL);
if (!$commentsQuery) die ('error with commentss query '.@mysql_error());
$num_comments=@mysql_num_rows($commentsQuery);



if ($_SESSION['uid'])
{
	$uinfoSQL="SELECT name, class, username FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
	
}



	
	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>Olentangy Life :. <?php echo $profile['name'];?>'s Profile</TITLE>
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
		<?php $_SESSION['login_redirect']=$_SERVER['PHP_SELF']; ?>
		 <input class="submit" type="image" name="login" src="../images/login.gif"> <a href="register.php"class="submit"><img src="../images/register.gif" alt="register"></a>
		 
    </p>
  </form>
   
   <?php 
   
   }
   
   ?>
   
   
   </div>
   <?php include ("../master_nav.php"); ?>
   
   
   <div id="main"><br/>
   
   <?php if ($profile['pic']) { ?>
  		 <a href="../pics<?php echo $profile['pic']; ?>" target="_BLANK"><img src="../blog/blogpic.php?pic=<?php echo $profile['pic']; ?>" align=left style="margin-right:10px;"></a>
   <?php } else { ?>
   		  <img src="../images/no_pic_big.gif" align=left style="margin-right:10px;"> <?php } ?>
   <h1><?php echo $profile['name']; ?></h1>
   <h2><?php echo $profile['username'];?></h2>
   <p><a class='blue' href="../blog/?u=<?php echo $profile['username']; ?>">go to blog</a> - <a class='blue' href="../albums/?u=<?php echo $profile['username']; ?>">view my albums</a><?php if ($person) { echo " - <a href='../private/compose.php?to=".$profile['username']."' class='blue'>send message</a>"; } ?></p>
   <p><?php echo str_replace("\n", "<br/>", $profile['profile']); ?></p>
 
</div>

  <div id="leftbar">
 	<center>
 	<h1 class='gold'>VITALS</h1><br/>
    <?php
    
    
    
    if ($profile['aim'])
   		{ 
   		echo "<p>AIM: <b><a href='aim:goim?screenname=".$profile['aim']."'>".$profile['aim']."</a></b>";
   	
   		
   		// Connect to AOL server
		$url = @fsockopen("big.oscar.aol.com", 80, &$errno, &$errstr, 3);
   		fputs($url, "GET /".$profile['aim']."?on_url=online&off_url=offline HTTP/1.0\n\n");
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
       echo "<br/><A HREF='aim:goim?screenname=".$profile['aim']."'><IMG SRC='../images/aimonline.gif' align='center' border='0' ALT='online'></A></p>";
   	
}else{
        echo "<br/><A HREF='aim:goim?screenname=".$profile['aim']."'><IMG SRC='../images/aimoffline.gif' align='center' border='0' ALT='online'></A></p>";

}

   		
   		} 
   		
   		
   		?>
   		
 	<p>class of: <b><a href='index.php?c=<?php echo $profile['class']; ?>'>200<?php echo $profile['class'];?></a></b></p>
 	<p><b><?php echo $num_posts;?></b> <a href='../blog/?u=<?php echo $profile['username']; ?>'>blog posts</a></b></p>
 	<p><b><?php echo $num_comments;?></b> comments left</b></p>
 	<p>member since: <b><?php echo date("m/d/y",$profile['joindate']); ?></b></p>
 	
 	<?php
 		$loginSQL="select datetime from logins where person='".$profile['id']."' order by datetime desc limit 1";
 		$loginQuery=@mysql_query($loginSQL);
 		if (!$loginQuery) {die  ('Error with last login query'.@mysql_error()); }
 		$lastLogin=@mysql_fetch_array($loginQuery);
 		echo "<p>last login: <b>".date("m/d/y",$lastLogin['datetime'])."</b></p>";
 		
 		
 	?>
 	<?php if ($profile['lastupdate']) { ?><p>last post: <b><a href="../blog/?u=<?php echo $profile['username']; ?>"><?php echo date("m/d/y",$profile['lastupdate']); ?></a></b></p><?php } ?>
 	
 	<h1 class='gold'>ACTIVITIES</h1><br/>
 	<?php 
			$actSQL="SELECT activities.name AS name, activities.id AS id FROM act_enrollments 
   	LEFT JOIN activities ON (activities.id = act_enrollments.activity)
   	WHERE (act_enrollments.person='".$profile['id']."') ORDER BY name ASC";
		$actQuery=@mysql_query($actSQL);
		if (!$actQuery) die ('Error with activities query '.@mysql_error());
		if (mysql_num_rows($actQuery)<1) echo "<p>no activities</p>";
		else
		{
			while ($activity=@mysql_fetch_array($actQuery))
			{
				echo "<p><b><a href='../people/?a=".$activity['id']."'>".$activity['name']."</a></b></p>";
			}
		
		}
		
	
	?>	
 	</center>
  </div>
   <div id="rightbar">
   		
   	</div>
  
   
  </BODY>
</HTML>