<?php

session_start();

include ("dbconnect.php");
include ("enrollfunctions.php");

if (!$_SESSION['uid']) die ('direct access not allowed');




if (check_enroll())
	{
		//$link="<Br/><br/><br/><br/><p>Your schedule has been processed. Click <a href='private/'>here</a> to access 'you', where you can create your profile, set up your blog, and make posts.</p>";
		$_SESSION['uid']=$person;
	}
	else
	{
		
		$link="<Br/><br/><br/><br/><p>There were errors processing your schedule. Please use your browser's back button to fix the problems.</p>";
	}


	header("cache-control:private");

?>

<html>
<head><title>Olentangy Life :. Registration Error</title>
<link rel="stylesheet" media="screen" href="olife.css">
</head>
<body>




<?php if ($link) echo $link; else {?>

<h1>WELCOME</h1>
<p>Welcome to Olentangy Life. To get started, click <a href="private/">here</a> to login to 'you'. 'You' is where you can go create your profile and blog, check your schedule, enroll in activities. and make posts.</p>";



<?php } ?>


</body>
</html>

