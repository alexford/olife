<?php

session_start();
header("cache-control:private");

include ('../dbconnect.php');

if (!$_SESSION['uid']) die ('How did you get here?');

	$uinfoSQL="SELECT username,id FROM people WHERE id=".$_SESSION['uid']." LIMIT 1";
	// echo $uinfoSQL;
	$uinfoQuery=@mysql_query($uinfoSQL);
	if (!$uinfoQuery) die ('Error with user info query!!! '.@mysql_error());
	if (@mysql_num_rows($uinfoQuery)==0) die ('INVALID USER ID. BAAAD');
	
	$person=@mysql_fetch_array($uinfoQuery);
	
	$albumSQL="SELECT * FROM albums WHERE id ='".$_POST['album']."'";
	$albumQuery=@mysql_query($albumSQL);
	if (!$albumQuery) die ('Error with album query '.@mysql_error());
	if (@mysql_num_rows($albumQuery)!=1) die ('<p><b>Album not found or multiple albums found.</b></p>');
	
	$album=@mysql_fetch_array($albumQuery);
	
	if ($album['creator']!=$person['id']) //not your album
		die ('<p><b>You cannot edit this album.</b></p>');
		
	// print_r($_POST);

	if (!$_FILES['image']['size']) die ('no image?');
	if ($_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/pjpeg")
	{
		//if ($_FILES['image']['size'] > 100000)
		//	die ('Your image is too big. Jerk. If you really wanna use that image, ask me and I might help you.');
		  $newname=$_FILES['image']['tmp_name'];
		//  echo $newname."<br/>";
		  
		  $newname=$newname.".jpg";
		 	
	 //  	echo $newname."<br/>";
 //		  echo  $_FILES['image']['tmp_name'];
 
 		$max_width = 100;    /////////THUMBNAIL////////////
$max_height = 100;

// Path to your jpeg


   $size = GetImageSize($_FILES['image']['tmp_name']); // Read the size
         $width = $size[0];
         $height = $size[1];
         
         // Proportionally resize the image to the
         // max sizes specified above
         
         $x_ratio = $max_width / $width;
         $y_ratio = $max_height / $height;

         if( ($width <= $max_width) && ($height <= $max_height) )
         {
               $tn_width = $width;
               $tn_height = $height;
         }
         elseif (($x_ratio * $height) < $max_height)
         {
               $tn_height = ceil($x_ratio * $height);
               $tn_width = $max_width;
         }
         else
         {
               $tn_width = ceil($y_ratio * $width);
               $tn_height = $max_height;
         }
     // Increase memory limit to support larger files
     
     ini_set('memory_limit', '32M');
     
     // Create the new image!
     $src = ImageCreateFromJpeg($_FILES['image']['tmp_name']);
     $dst = ImageCreateTrueColor($tn_width, $tn_height);
     ImageCopyResampled($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
     ImageJpeg($dst,"../album_pics/thumbs".$newname);
    // die($newname);
     
// Destroy the image

 
 if ($width > 500)
  {
 
 	ImageDestroy($dst);
      
      		$max_width = 500;    /////////FULL SIZE////////////
$max_height = 1000;
         $x_ratio = $max_width / $width;
         $y_ratio = $max_height / $height;


	

         if( ($width <= $max_width) && ($height <= $max_height) )
         {
               $tn_width = $width;
               $tn_height = $height;
         }
         elseif (($x_ratio * $height) < $max_height)
         {
               $tn_height = ceil($x_ratio * $height);
               $tn_width = $max_width;
         }
         else
         {
               $tn_width = ceil($y_ratio * $width);
               $tn_height = $max_height;
         }	
 	
 	$dst = ImageCreateTrueColor($tn_width, $tn_height);
     ImageCopyResampled($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
	ImageJpeg($dst,"../album_pics".$newname);
 	
 }
 else ImageJpeg($src,"../album_pics".$newname);
 
  if ($width > 200)
  {
 
 	ImageDestroy($dst);
      
      		$max_width = 200;    /////////BLOG SIZE////////////
$max_height = 500;
         $x_ratio = $max_width / $width;
         $y_ratio = $max_height / $height;


	

         if( ($width <= $max_width) && ($height <= $max_height) )
         {
               $tn_width = $width;
               $tn_height = $height;
         }
         elseif (($x_ratio * $height) < $max_height)
         {
               $tn_height = ceil($x_ratio * $height);
               $tn_width = $max_width;
         }
         else
         {
               $tn_width = ceil($y_ratio * $width);
               $tn_height = $max_height;
         }	
 	
 	$dst = ImageCreateTrueColor($tn_width, $tn_height);
     ImageCopyResampled($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
	ImageJpeg($dst,"../album_pics/blog".$newname);
 	
 }
 else ImageJpeg($src,"../album_pics/blog".$newname);
 
 
 
 	
 ImageDestroy($dst);
 ImageDestroy($src);

 
 
		 // copy ($_FILES['image']['tmp_name'], "../album_pics".$newname) or die ("Could not copy");
		  $picSQL="INSERT INTO album_pics (creator,date,file,name,description) VALUES 
		  ('".$person['id']."','".time()."', '".$newname."', '".strip_tags($_POST['name'])."','".strip_tags($_POST['description'])."')";
		  $picQuery=@mysql_query($picSQL);
		  if (!$picQuery) die ('Error with pic query '.@mysql_error());
		  	  $picid = @mysql_insert_id();
		  
		  $updateSQL = "UPDATE albums SET lastupdate='".time()."' WHERE id='".$album['id']."'";
		  $picQuery=@mysql_query($updateSQL);
		  if (!$picQuery) die ('Error with update query '.@mysql_error());
		  
		  
	
		  $pairSQL="INSERT INTO album_pairs (album,album_pic,date) VALUES ('".$album['id']."','".$picid."','".time()."')";
		  $pairQuery=@mysql_query($pairSQL);
		  if (!$pairQuery) die ('Error with pairing query '.@mysql_error());
		   
		 
		  
	}
	else die ('Your file is not a jpeg. I think it is a '.$_FILES['image']['type'].' Please use your browsers back button and select a jpeg.');


// echo "<b>".$saveSQL."</b>";


Header ("Location:editalbum.php?id=".$album['id']);

?>