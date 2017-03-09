<?php

include ('../dbconnect.php');

$sql="UPDATE people SET ss_tean='".$_GET['team']."' where id = '".$_GET['person']."'";
$query=@mysql_query($sql);
if (!$query) die (mysql_error());


?>
