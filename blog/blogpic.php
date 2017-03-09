<?php
// Max height and width
$max_width = 160;
$max_height = 200;

// Path to your jpeg

$filename = $_GET['pic'];

$upfile = "../pics".$filename;

   Header("Content-type: image/jpeg");
   
   $size = GetImageSize($upfile); // Read the size
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
     $src = ImageCreateFromJpeg($upfile);
     $dst = ImageCreateTrueColor($tn_width, $tn_height);
     ImageCopyResampled($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
     ImageJpeg($dst);
// Destroy the images
ImageDestroy($src);
ImageDestroy($dst);
?>