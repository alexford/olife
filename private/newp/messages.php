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
    	
    	function submit2ndForm()
    	{
    		document.forms[1].submit()
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

<h1>MESSAGES</h1>
<p>From here, you can exchange private messages with Olentangy Life users.  Think of it like email, except its free, there's no spam or ads, and only Olife members can use/read it.  You can also un-send messages and check to see if the recipient has read them.</p>


<h1 class='gold' style='margin-top:0px;'>INBOX</h1>
<form method="get" action="messages.php"> 

<?php
		if ($_GET['s']=='all') $limit="";				//figure out the limit
			else if (!$_GET['s']) $limit='LIMIT 10';
			else $limit="LIMIT ".$_GET['s'];
		
		$postsSQL="SELECT messages.subject AS subject, messages.datetime AS datetime, messages.id AS id, messages.read AS message_read, people.name AS sender_name, people.username AS sender_uname FROM messages LEFT JOIN people ON (messages.sender = people.id) WHERE (messages.recipient = '".$person['id']."' OR messages.recipient='all') AND messages.deleted='0' AND messages.unsent='0' ORDER BY datetime DESC ".$limit;
		//echo $postsSQL;
		$postsQuery=@mysql_query($postsSQL);								//do the query
		if (!$postsQuery) die ('Error with posts query '.@mysql_error());
		
		echo "<p><b>Showing <select name='s' onChange='submitForm()'><option value='10'";
		
		if ($_GET['s']=='10') echo "SELECTED";
		echo ">10</option><option value='15'";
		if ($_GET['s']=='15') echo "SELECTED";
		echo ">15</option><option value='30'";
		if ($_GET['s']=='30') echo "SELECTED";
		echo ">30</option><option value='50'";
		if ($_GET['s']=='50') echo "SELECTED";
		echo ">50</option><option value='all'";
		if ($_GET['s']=='all') echo "SELECTED";
		echo ">All</option></select>messages. ".@mysql_num_rows($postsQuery)." messages total.</b></p></form>";
		
		echo "<table cellpadding=5 width=100%>";
		echo "<tr class='header'><td></td><td></td><td></td><td><p>date</p></td><td><p>subject</p></td><td><p>sender</p></tr>";
		$even=0;
	
		while ($message=@mysql_fetch_array($postsQuery))
		{
			if ($even==0) { $even=1; echo "<tr class='listodd'>"; }
				else	  { $even=0; echo "<tr class='listeven'>";}
			
			echo "<td>";
				if ($message['message_read']==0) echo "<a href='readmessage.php?id=".$message['id']."'><img style='border:0px;' src='../images/unread.gif' alt='unread'></a>";
					else					     echo "<a href='readmessage.php?id=".$message['id']."'><img style='border:0px;' src='../images/read.gif' alt='read'></a>";
			
			echo "</td>"; //read/unread image
			echo "<td><p>";
			if ($message['message_read']==0) echo "<b>";
			echo "<a class='blue' href='deletemessage.php?id=".$message['id']."'>delete</a>";
			if ($message['message_read']==0) echo "</b>";
			echo "</p></td>"; //delete
			
						echo "<td><p>";
			if ($message['message_read']==0) echo "<b>";
			echo "<a class='blue' href='compose.php?replyid=".$message['id']."'>reply</a>";
			if ($message['message_read']==0) echo "</b>";
			echo "</p></td>"; //reply

			echo "<td><p>";
			if ($message['message_read']==0) echo "<b>";
				echo date('n/j g:iA',($message['datetime']+(60*60*3))); //post date
			if ($message['message_read']==0) echo "</b>";
			echo "</p></td>";
			
			echo "<td width=*><p>";
				if ($message['message_read']==0) echo "<b>";
					echo "<a class='blue' href='readmessage.php?id=".$message[id]."'>".$message['subject']."</a>";
				if ($message['message_read']==0) echo "</b>";
			echo "</p></td>"; //subject
			
			echo "<td><p>";
			if ($message['message_read']==0) echo "<b>";
				echo "<a class='blue' href='../people/profile.php?u=".$message['sender_uname']."'>".$message['sender_name']."</a>";
			if ($message['message_read']==0) echo "</b>";
			
			echo "</p></td>";
			
			
			
			echo "</tr>";
			
		
		}
echo "</table>";

if (@mysql_num_rows($postsQuery)==0) echo "<p>You have no messages.</p>";
?>

</div>

 
  
   
  </BODY>
</HTML>