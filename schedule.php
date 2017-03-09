<?php

session_start();
header("cache-control:private");

include ('dbconnect.php');



$periodsSQL="SELECT * FROM periods ORDER BY id ASC";
$periodsQuery=@mysql_query($periodsSQL);
if (!$periodsQuery) die ('Error with periods query: '.@mysql_error());
$debug=@mysql_num_rows($periodsQuery);


while ($pData=@mysql_fetch_array($periodsQuery))
{
	$periodsArray[$pData['id']]=$pData['display'];

}

$classesSQL="SELECT * FROM classes ORDER BY name ASC";
$classesQuery=@mysql_query($classesSQL);
if (!$classesQuery) die ('Error with classes query: '.@mysql_error());

while ($cData=@mysql_fetch_object($classesQuery))
{
	$classesArray[$cData->id]=$cData->name;
}

$teachersSQL="SELECT * FROM teachers ORDER BY name ASC";
$teachersQuery=@mysql_query($teachersSQL);
if (!$teachersQuery) die ('Error with teachers query: '.@mysql_error());


while ($tData=@mysql_fetch_object($teachersQuery))
{
	$teachersArray[$tData->id]=$tData->name;
	
}

mysql_close();

	
	?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>Olentangy Life :. Schedule</TITLE>
    <link rel="stylesheet" media="screen" href="olife.css">
  </HEAD>
  <BODY>
   <div id="title"><img src="images/olentangy_life.gif"></div>
  
   <div id="tagline">the best olentangy student community ever.</div>
   <div id="bar"></div>
   <div id="login">
   
   
   
   </div>
   <div id="nav"><a href='index.php'>HOME</a> <a href='about.php'>ABOUT</a> <a href="people/">PEOPLE</a> <a href="blogs/">BLOGS</a></div>
   
   <div id="leftbar" align='right'>
   
  
   <center><h2 class="gold">WHY?</h2>
   <p>
	One of the cool things about Olentangy Life is that it automatically
	connects you with members of your classes and activities.  In order to do this, 
	you need to enter your schedule here. It is very important to be accurate. I know
	this part of registration is a huge pain, but you only have to do it once.<br/><br/>
	
	Make sure you select a teacher for every class, even if you don't have a teacher for that class! 
	Just select "None/lunch" for 'special' periods and lunches. <br/><br/>
	
	Also, make sure you select a class and a period for each schedule slot. If you have 
	early dismissal (for whatever reason) or any other circumstances that make you not have a 
	class that period, select 'SPECIAL' as your class.<br/><br/>
	
	<b>If your teacher isnt listed (ie is new to the district)</b>, just select "none/lunch".
	</p>
	
   </center>
   </div>
   
   
   
   <div id="main">
	<form method='post' action='submitschedule.php'>
	<h1>YOUR SCHEDULE</h1>

<div class="box">
<table cellspacing=10>
<td>
	<h2>SEMESTER ONE</h2>
	
<?php

for ($x=1;$x<=9;$x++) //for each period
{
	echo "<table class='box'><tr valign=center><td>";    ////period selecter
	echo "<select name='s1p".$x."period'>";
	echo "<option value=''>Period</option>";
	foreach ($periodsArray as $i => $period)
	{
		echo "<option value='".($i)."'>".$period."</option>";
	}
	echo "</select>";
	echo "<select name='s1p".$x."teacher'>";
	echo "<option value=''>Teacher</option>";
	echo "<option value='0'>None/Lunch</option>";
	foreach ($teachersArray as $i => $teacher)
	{
		echo "<option value='".($i)."'>".$teacher."</option>";
	}
	echo "</select>";
	echo "</td>";
	
	echo "<td>";

	
	echo "</td>";
	
	
	echo "</tr><tr><td><select name='s1p".$x."class'>";
	echo "<option value=''>Class</option>";
	echo "<option value='0'>-SPECIAL-</option>";
	foreach ($classesArray as $i => $class)
	{
		echo "<option value='".($i)."'>".$class."</option>";
	}
	echo "</select></td><td></td></tr>";



	echo "</table>";
} //for each period
	
?>
<br/><br/>

	<h2>SEMESTER TWO</h2>
	
	<?php

for ($x=1;$x<=9;$x++) //for each period
{
	echo "<table class='box'><tr valign=center><td>";    ////period selecter
	echo "<select name='s2p".$x."period'>";
	echo "<option value=''>Period</option>";
	foreach ($periodsArray as $i => $period)
	{
		echo "<option value='".($i)."'>".$period."</option>";
	}
	echo "</select>";
	echo "<select name='s2p".$x."teacher'>";
	echo "<option value=''>Teacher</option>";
	echo "<option value='0'>None/Lunch</option>";
	foreach ($teachersArray as $i => $teacher)
	{
		echo "<option value='".($i)."'>".$teacher."</option>";
	}
	echo "</select>";
	echo "</td>";
	
	echo "<td>";

	
	echo "</td>";
	
	
	echo "</tr><tr><td><select name='s2p".$x."class'>";
	echo "<option value=''>Class</option>";
	echo "<option value='0'>-SPECIAL-</option>";
	foreach ($classesArray as $i => $class)
	{
		echo "<option value='".($i)."'>".$class."</option>";
	}
	echo "</select></td><td></td></tr>";
	echo "</table>";
} //for each period

?>
</td>
<td valign="top">
	 <p><b>I'm gonna stress this again because I'm pretty sure you didn't read it on the left...</b></p><Br/>
	 <h2>BE ACCURATE WHEN YOU FILL OUT YOUR SCHEDULE</h2><br/>
	 <h2>SELECT A CLASS, PERIOD, AND TEACHER FOR EVERY BOX</h2><br/>
	 <p>Read the left part again, just to be sure.</p>
</td>
</table>

</div>
<div align='center'><button type="submit">Continue...</button></div>
	</form>
   		

</div>

   <div id="rightbar">
   		
   				
   	</div>
  
   
  </BODY>
</HTML>