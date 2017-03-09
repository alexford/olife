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


<h1>SENT MESSAGES</h1>
<form method="get" action="messages.php"> 

<?php
		if ($_GET['ss']=='all') $limit="";				//figure out the limit
			else if (!$_GET['ss']) $limit='LIMIT 10';
			else $limit="LIMIT ".$_GET['ss'];
		
		$postsSQL="SELECT messages.subject AS subject, messages.datetime AS datetime, messages.id AS id, messages.read AS message_read, people.name AS sender_name, people.username AS sender_uname FROM messages LEFT JOIN people ON (messages.recipient = people.id) WHERE (messages.sender = '".$person['id']."') AND (messages.unsent='0') AND (messages.recipient!='0') ORDER BY datetime DESC ".$limit;
		//echo $postsSQL;
		$postsQuery=@mysql_query($postsSQL);								//do the query
		if (!$postsQuery) die ('Error with posts query '.@mysql_error());
		
		echo "<p><b>Showing <select name='ss' onChange='submit2ndForm()'><option value='10'";
		
		if ($_GET['ss']=='10') echo "SELECTED";
		echo ">10</option><option value='15'";
		if ($_GET['ss']=='15') echo "SELECTED";
		echo ">15</option><option value='30'";
		if ($_GET['ss']=='30') echo "SELECTED";
		echo ">30</option><option value='50'";
		if ($_GET['ss']=='50') echo "SELECTED";
		echo ">50</option><option value='all'";
		if ($_GET['ss']=='all') echo "SELECTED";
		echo ">All</option></select>messages. ".@mysql_num_rows($postsQuery)." messages total.</b></p></form>";
		
		echo "<table cellpadding=5 width=100%>";
		echo "<tr class='header'><td><p>read</p></td><td></td><td><p>date</p></td><td><p>subject</p></td><td><p>sent to</p></tr>";
		$even=0;
	
		while ($message=@mysql_fetch_array($postsQuery))
		{
			if ($even==0) { $even=1; echo "<tr class='listodd'>"; }
				else	  { $even=0; echo "<tr class='listeven'>";}
			
			echo "<td>";
				if ($message['message_read']==0) echo "<p>not yet</p>";
					else					     echo "<p><b>yes</b></p>";
			
			echo "</td>"; //read/unread image
			echo "<td><p>";
		
			echo "<a class='blue' href='unsendmessage.php?id=".$message['id']."'>unsend</a>";
			
			echo "</p></td>"; //delete
			
					

			echo "<td><p>";
		
				echo date('n/j g:iA',($message['datetime']+(60*60*3))); //post date
			
			echo "</p></td>";
			
			echo "<td width='*'><p>";

					echo $message['subject'];
				
			echo "</p></td>"; //subject
			
			echo "<td><p>";
			
				echo "<a class='blue' href='../people/profile.php?u=".$message['sender_uname']."'>".$message['sender_name']."</a>";
			
			
			echo "</p></td>";
			
			
			
			echo "</tr>";
			
		
		}
echo "</table>";
?>

</div>

 
  
   
  </BODY>
</HTML>