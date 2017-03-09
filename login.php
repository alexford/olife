<?php include ("dbconnect.php");
	session_start();
	header("cache-control: private");
?>

<html>
<head><title>Olentangy Life :. Login</title>
<link rel="stylesheet" media="screen" href="olife.css">
</head>
<body>

<center>

<br/><br/><br/><br/>
<form method='post' action='logincheck.php'>
<table class="box">
<tr>
	<td></td>
	<td><h2>LOGIN</h2></td>
</tr>
<tr>
	<td><p><b>USERNAME:</b></p></td>
	<td><input type="text" name="username"></td>
</tr>

<tr>
	<td><p><b>PASSWORD:</b></p></td>
	<td><input type="password" name="password"></td>
</tr>

<?php

if ($_SESSION['login_redirect'])
{
?>

<tr>
	<td><p><b>AFTER LOGIN:</b></p></td>
	<td>
	
		<table>
		<tr><td><input type="radio" name="redirect" value="private/"></td><td><p>YOU</p></td></tr>
		<tr><td><input type="radio" checked name="redirect"></td><td><p>LAST PAGE VISITED</p></td></tr>
		</table>
		
	</td>
</tr>

<?php

}
else
{ 
 $_SESSION['login_redirect']="private/";
 
} 
?>


<tr>
	<td></td>
	<td><br/><button type="submit">Login</button></td>
</tr>
</table>
</form>

</center>

</body>
</html>