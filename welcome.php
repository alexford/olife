<?php

session_start();

include ("dbconnect.php");

if (!$_SESSION['uid']) die ('direct access not allowed');

$uinfoSQL="SELECT name, class, username, id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);


?>

<html>
<head><title>Olentangy Life :. Welcome</title>
<link rel="stylesheet" media="screen" href="olife.css">
</head>
<body style="padding:50px;">
<center>


<h1>SENIOR SHOOTOUT?!</h1>
<p><b>WAHEY!</b> Welcome to the ultimate club. Some advice:</p>
<p>1.) Senior Shootout is all about respect.</p>
<p>2.) Do not get naked.</p>
<p>3.) Do not do anything illegal.</p>
<p>4.) Don't lie (you ruin everything if you do)</p>
<p>5.) DJ and the others in charge are probably smarter than you.</p>
<p>6.) If something goes wrong with the site, odds are it's my fault, so 
yell at me. If you want to argue about whether Lewis Center road is school 
grounds or if shooting out a car window is illegal, by all means call DJ 
on his cell phone 24 hours a day.</p>
 <p>That being said, go <A 
href='http://shootout.olentangylife.com'/>here</a> to see if those losers 
have done anything yet.</p>
<p style='text-align:right;'>--Alex Ford, OHS '06</p> 

<h1>WELCOME</h1>
<p>Welcome to Olentangy Life, <?php echo $person['name']; ?>. To get started, click <a href="private/">here</a> to login to 'you'. 
'You' is where you can go create your profile and blog, check your schedule, enroll in activities. and make posts.</p>

<p>The first thing you will want to do is enter your schedule by clicking on 'schedule'. This will connect you with other Olife Members immediately. After that, be sure to 
set up your profile and blog settings, then click 'posts' to start posting to your blog.</p>



</center>
</body>
</html>

