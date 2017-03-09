<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');

$section="messages";

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
    <SCRIPT type="text/javascript" language ="JavaScript">
    	function submitForm()
    	{
    		document.forms[0].submit()
    	}
    	
    	function confirmDelete(id)
    	{
    		input_box=confirm("Are you sure you want to delete this message? It cannot be undone.");
			if (input_box==true)
			{
				document.location.href = "deletemessage.php?id="+id
			}	
		}
    </SCRIPT>
    
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


<p><b><a href='messages.php' class='blue'>back to messages</a></b></p>
<h1 class='gold' style='margin-top:0px;'>MESSAGE VIEW</h1>

<?php
		if (!$_GET['id']) die ("<p>No message specified</p>");
		$postsSQL="SELECT messages.message AS message, messages.subject AS subject, messages.recipient AS recipient, messages.datetime AS datetime, messages.id AS id, messages.read AS message_read, profile_pics.filename AS pic, people.class AS class, people.sex AS sex, people.name AS sender_name, people.username AS sender_uname FROM messages LEFT JOIN people ON (messages.sender = people.id) LEFT JOIN profile_pics ON (people.pic = profile_pics.id) WHERE messages.id='".$_GET['id']."' AND messages.unsent!='1'";
		$postsQuery=@mysql_query($postsSQL);								//do the query
		if (!$postsQuery) die ('Error with posts query '.@mysql_error());
		$message=@mysql_fetch_array($postsQuery);
		if ($message['recipient']!=$person['id'] && $message['recipient']!='all') die("<p>You are not authorized to view this message.</p></div>");
		if ($message['deleted']=='1') die ('<p>Message not found.</p></div>');
		if ($message['message_read']=='0')  //we need to update to read
		{
			$readSQL="UPDATE messages SET messages.read=1 WHERE id = '".$_GET['id']."'";
			//echo $readSQL;
			$readQuery=@mysql_query($readSQL);
			if (!$readQuery) die ('Error with update query '.@mysql_error());
			
		}
		echo "<table cellspacing=10>";
			echo "<td valign='top'>";
			if ($message['pic']) echo "<a href='../people/profile.php?u=".$message['sender_uname']."'><img style='border:none; margin-bottom:10px;' src='../people/userpic.php?pic=".$message['pic']."' align='left'></a>";
			
			echo "<div align='right'><p><b><a class='blue' href='compose.php?replyid=".$_GET['id']."'>reply</a><br/><a class='blue' href='deletemessage.php?id=".$_GET['id']."'>delete</a></b></p></div>";
				
			
			echo "</td>";

			echo "<td valign='top'>";
			
				echo "<p><b>TO:</b> <a class='blue' href='../people/profile.php?u=".$person['username']."'>".$person['name']." (".$person['username'].")</a><br/>";
				echo "<b>FROM:</b> <a class='blue' href='../people/profile.php?u=".$message['sender_uname']."'>".$message['sender_name']." (".$message['sender_uname'].")</a><br/>";
				echo "<b>DATE:</b> ".date('l n/j g:iA',($message['datetime']+(60*60*3))); //post date
				echo "</p>"; //^^ USER INFO
				
						//^^ action links
		
			

				echo "<h2>".$message['subject']."</h2>";
					
				
				$theString = str_replace("\n", "<br />", $message['message']);	
				$smileysSQL="SELECT name, ocode, file FROM smileys ORDER BY NAME asc";
				$smileysQuery=@mysql_query($smileysSQL);
				if (!$smileysQuery) die ('Error with smileys query'.@mysql_error());
	
	
				while ($smiley=@mysql_fetch_array($smileysQuery))
				{
					$theString=str_replace($smiley['ocode'],"<img src='../smileys/".$smiley['file']."' ALT='".$smiley['name']."'>",$theString);
		
				}
			echo "<p>".$theString."</p>";
			
			echo "</td>";
				echo "</table>";

?>


</div>

 
  
   
  </BODY>
</HTML>