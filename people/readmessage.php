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
   
   
      echo "<p><b>LOGGED IN AS ".$person['name']." (".$person['username'].") - <a href='../logout.php'>LOGOUT</a></b></p>";
  
   ?>
   
   </div>
   <div id="nav"><a href='../index.php'>HOME</a> <a href='../about.php'>ABOUT</a> <a href="../people/">PEOPLE</a> <a href="../blogs/">BLOGS</a></div>
   
   <div id="privateleftbar" align=right>
   
  
  
	<?php include('privatenav.html'); ?>
   	
   
   </div>
   
   
   
   <div id="mainnoright">



<h1 class='gold' style='margin-top:0px;'>MESSAGE VIEW</h1>

<?php
		
		$postsSQL="SELECT messages.subject AS subject, messages.datetime AS datetime, messages.id AS id, messages.read AS message_read, people.pic AS pic, people.class AS class, people.sex AS sex, people.name AS sender_name, people.username AS sender_uname FROM messages LEFT JOIN people ON (messages.sender = people.id) WHERE messages.id='".$_GET['id'].";
		$postsQuery=@mysql_query($postsSQL);								//do the query
		if (!$postsQuery) die ('Error with posts query '.@mysql_error());
		
		
		while ($message=@mysql_fetch_array($postsQuery))
		{
			if ($even==0) { $even=1; echo "<tr class='listodd'>"; }
				else	  { $even=0; echo "<tr class='listeven'>";}
			
			echo "<td>";
				if ($message['message_read']==0) echo "<img style='border:0px;' src='../images/unread.gif' alt='unread'>";
					else					     echo "<img style='border:0px;' src='../images/read.gif' alt='read'>";
			
			echo "</td>"; //read/unread image
			echo "<td><p>";
			if ($message['message_read']==0) echo "<b>";
			echo "<a class='blue' href='deletemessage.php?id=".$message['id']."' onclick='confirmDelete(".$message['id'].")'>delete</a>";
			if ($message['message_read']==0) echo "</b>";
			echo "</p></td>"; //delete
			
						echo "<td><p>";
			if ($message['message_read']==0) echo "<b>";
			echo "<a class='blue' href='reply.php?id=".$message['id']."'>reply</a>";
			if ($message['message_read']==0) echo "</b>";
			echo "</p></td>"; //reply

			echo "<td><p>";
			if ($message['message_read']==0) echo "<b>";
				echo date('n/j g:iA',($message['datetime']+(60*60*3))); //post date
			if ($message['message_read']==0) echo "</b>";
			echo "</p></td>";
			
			echo "<td><p>";
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

?>


</div>

 
  
   
  </BODY>
</HTML>