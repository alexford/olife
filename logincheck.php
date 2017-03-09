<?php include ("dbconnect.php");

	$userSQL="SELECT * FROM people WHERE username = '".$_POST['username']."'";
	$userQuery=@mysql_query($userSQL);
	if (!$userQuery) die ('Error with user query '.@mysql_error());
	$userData=@mysql_fetch_array($userQuery);
	
	if (@mysql_num_rows($userQuery)==0)
	{
		$message="That username was not found. Be sure you typed it correctly and that you have registered.";
	}
	else
	{
		if ($userData['password']!=md5($_POST['password']))
		{
			$message="I found your username, but your password was wrong.";
	
		}
		else
		{
		
			session_start();
			$_SESSION['uid']=$userData['id'];
			
			$logSQL="INSERT INTO logins (datetime,person,useragent) VALUES ('".time()."','".$userData['id']."','".$_SERVER['REMOTE_ADDR']." ".$_SERVER['HTTP_USER_AGENT']."')";
			$logQuery=@mysql_query($logSQL);
			if (!$logQuery) die ('Error writing to login log. '.@mysql_error());
			
			if ($_POST['redirect']=="private/") Header("Location: private/");
			else Header("Location: ".$_SESSION['login_redirect']);
		
		}
	}
	
		

?>

<html>
<head><title>Olentangy Life :. Login Failed</title>
<link rel="stylesheet" media="screen" href="olife.css">
</head>
<body>

<center>

<p><b>There were problems logging in.</b><br /><br />
Please use your browser's back button to fix the problem(s).</p>
<?php
	echo "<p class='red'><b>".$message."</b></p>";
	
	//echo $userData['password']."<br/>";
	//echo $_POST['pw']."<Br/>";
	//echo md5($_POST['pw'])."<br/>";
	//echo md5('test');
?>


</center>


</body>
</html>