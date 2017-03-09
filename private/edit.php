<?php //edit post.. MUST BE LOGGED IN
include ("../dbconnect.php");
session_start();

$postsSQL="SELECT * FROM posts WHERE id = '".$_GET['id']."' LIMIT 1";
$postsQuery=@mysql_query($postsSQL);
if (!$postsQuery) die ('Error with posts query! '.@mysql_error());
$post=@mysql_fetch_array($postsQuery);

if ($post['person']!=$_SESSION['uid']) die ('<h1>YOU CAN NOT EDIT THIS POST.</h1>');




?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<HTML>
  <HEAD>
    <TITLE>Edit <?php echo $post['heading']; ?> :. Olentangy Life</TITLE>
    <link rel="stylesheet" media="screen" href="../olife.css">
  </HEAD>
  <body><br/><br/><br/>
  <center><h1>Edit Post</h1>
  <form method='post' action='updatepost.php'>
  <input type="hidden" name="postid" value="<?php echo $post['id']; ?>">
   			<table class="form">
   			<tr>
   				<td align="right"><p><b>HEADING (optional):</b></p></td>
   				<td><input type="text" name="heading" value="<?php echo $post['heading']; ?>" size="32"></td>
   			</tr>
   			
   			<tr>
   				<td valign="top" align="right"><p><b>BODY (required):</b></p></td>
   				<td><textarea rows="14" cols="40" name="body"><?php echo $post['body']; ?></textarea></td>
   			</tr>
   			
   			<tr>
   				<td width=20% valign="top" align="right"><p><b>PICTURE:</b><br/></p></td>
   				<td width=80% valign="top">
   				<?php
   					//list all pictures -- might be changed as more people add more pictures.
   					$album_sql = "SELECT id,name FROM albums WHERE creator='".$_SESSION['uid']."' AND access='0'";
   					$album_query=@mysql_query($album_sql);
   					if (!$album_query) die ('Error with album query'.@mysql_error());
   					
   					if (@mysql_num_rows($album_query)!=0)  //do they have albums???
   					{
   						echo "<select name='picture'>";
   						echo "<option ";
   							if ($post['picture']==0) echo "SELECTED";
   						echo " value='0'>no picture</option>";
   						while ($album=@mysql_fetch_array($album_query))
   						{
   							 echo "<optgroup label='".$album['name']."'>";
   							 $picture_sql = "SELECT album_pics.id AS pic_id, album_pairs.id AS id, album_pics.name AS name FROM album_pairs LEFT JOIN album_pics ON (album_pics.id = album_pairs.album_pic) WHERE album_pairs.album='".$album['id']."'";
   							 $picture_query=@mysql_query($picture_sql);
   							 if (!$picture_query) die ('Error with picture query'.@mysql_error());
   							 while ($picture=@mysql_fetch_array($picture_query))
   							 {
   							 	echo "<option value='".$picture['id']."' ";
   							 		if ($post['picture']==$picture['id']) echo "SELECTED";
   							 	echo " >".$picture['name']."</option>";
   							 }
   							 echo "</optgroup>";
   						}
   						echo "</select>";
   					}
   				?>
   				</td>
   			</tr>
   			
   			<tr>
   				<td valign="top" align="right"><p><b>TAKE ME:</b></p></td>
   				<td>
   					<table>
   					<tr><td><input type="radio" name="redirect" value="blog" checked></td>
   						<td><p><b>To My Blog</b></p></td></tr>
   					<tr><td><input type="radio" name="redirect" value="private" checked></td>
   						<td><p><b>To 'you'</b></p></td></tr>
   					</table>
   				</td>
   			</tr>
   			<tr><td></td>
   				<td><button type="submit">POST</button</td>
   			</tr>
   			</table>
   		</form>
   		
   		
  </center>
  </body>
  
  
  </htmk>
  