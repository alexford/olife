<?php

session_start();
header("cache-control:private");

include ("dbconnect.php");
// include ("enrollfunctions.php");



$goFlag=1;

//Here I am going to check to make sure the form was filled out correctly, and stuff.

$message="<p>";
if ($_POST['email']!=$_POST['emailc'])
{
	$message=$message."<i>Your email address fields do not match.</i><br/><br/>";
	$link="<p><b>Please use your browser's back button to fix the error(s).</b></p>";
	$goFlag=0;
}

$emailSQL="SELECT username,email FROM people";
$emailQuery=mysql_query($emailSQL);

while ($emailData=@mysql_fetch_array($emailQuery))
{
	if ($_POST['username']==$emailData['username'] || $_POST['email']==$emailData['email'])
	{
		$message=$message."<i>This username or email address has already been registered.</i><br/><br/>";
		$link="<p><b>Please use your browser's back button to fix the error(s).</b></p>";
		$goFlag=0;
	}
}

	$bansSQL="SELECT email FROM bans";
	$bansQuery=@mysql_query($bansSQL);
	if (!$bansQuery) die ('Error with bans query '.@mysql_error());
	
	while ($ban=@mysql_fetch_array($bansQuery))
	{
		if ($ban['email']==$_POST['email'])
			die ("<html><head><title>You Have Been Banned</title></head><body><br/><br/><br/><center><img src='http://www.olentangylife.com/images/mickeyowned.jpg'></center></body></html>");
	}

if (!$_POST['username'])
{
	$message=$message."<i>You didn't enter a username.</i><br/><br/>";
	$link="<p><b>Please use your browser's back button to fix the error(s).</b></p>";
	$goFlag=0;
}
if ($_POST['pw']!=$_POST['pwc'])
{
	$message=$message."<i>Your password fields do not match.</i><br/>";
	$link="<p><b>Please use your browser's back button to fix the error(s).</b></p>";
	$goFlag=0;
}

if (!$_POST['pw'])
{
	$message=$message."<i>You didn't enter a password.</i><br/><br/>";
	$link="<p><b>Please use your browser's back button to fix the error(s).</b></p>";
	$goFlag=0;
}

if (!$_POST['name'])
{
	$message=$message."<i>You didn't enter your name.</i><br/><br/>";
	$link="<p><b>Please use your browser's back button to fix the error(s).</b></p>";
	$goFlag=0;
}


if ($goFlag==1)
{

}


if (!$link) //if there havent been any errors
{
	


$personSQL="INSERT INTO people (username,name,class,email,aim,sex,password,theme,joindate,reg_agent) VALUES ('".strip_tags($_POST['username'])."','".strip_tags($_POST['name'])."','".$_POST['class']."','".$_POST['email']."','".strip_tags($_POST['aim'])."','".$_POST['sex']."','".md5($_POST['pw'])."','1','".time()."','".$_SERVER['REMOTE_ADDR']." ".$_SERVER['HTTP_USER_AGENT']."')";
$personQuery=@mysql_query($personSQL);
if (!$personQuery) die ('Error creating person '.@mysql_error());

$id=mysql_insert_id();

mysql_close();

$_SESSION['uid']=$id;
// die ($id['lastid']);
		
		header("Location: welcome.php");
	
}
else
{
?>

<html>
<head><title>Olentangy Life :. Registration Error</title>
<link rel="stylesheet" media="screen" href="olife.css">
</head>
<body>



<center>

<?php echo $message; ?>


<?php echo $link; ?>

</center>



</body>
</html>

<?php }

?>
