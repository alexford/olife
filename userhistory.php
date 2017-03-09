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
	
$subject=$_GET['id'];
$traceSQL="SELECT * FROM people WHERE id='".$subject."'";
$traceQuery=@mysql_query($traceSQL);
if (!$traceQuery) die (@mysql_error());

$trace=@mysql_fetch_array($traceQuery);

	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>User History</TITLE>
    <link rel="stylesheet" media="screen" href="olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="images/olentangy_life.gif" ALT='Olentangy Life'></div>
  
   <div id="tagline">the best olentangy student community ever.</div>
   <div id="bar"> </div>
   <div id="login">
  
   
   </div>

   
   <div id="main">
    
   <p><b>Username</b>: <?php echo $trace['username']; ?></p>
   	<p><b>'Real' Name</b>: <?php echo $trace['name']; ?></p>
   	<p><b>email</b>: <?php echo $trace['email']; ?></p>
   	<p><b>md5</b>: <?php echo $trace['password']; ?></p>
   	<p><b>aim</b>: <?php echo $trace['aim']; ?></p>
   	<p><b>reg_agent</b>: <?php echo $trace['reg_agent']; ?></p>

	<?php 
	  $ipSQL="SELECT useragent FROM logins WHERE person='".$trace['id']."' order by id desc"; 
	  $ipQuery=@mysql_query($ipSQL);
	  
	  echo "<p><b>Log-in IPs...</b> (most recent first)<Br/>";
	  while ($ip=@mysql_fetch_array($ipQuery))
	  {
	  	echo $ip['useragent']."<br/>";
	  }
	  echo "</p>";
	  
	  ?>
	

</div>


  
   
  </BODY>
</HTML>