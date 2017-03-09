	<h2><a class="pnav" href="index.php">YOU</a></h2>
	<?php if ($section=="you")
	{ ?>
		<p style='border-right:1px solid #ccc; margin-right:5px;padding-right:2px;'><a class='blue' href="profile.php">profile</a><br/>
		<a class='blue' href="schedule.php">schedule</a><br/>
		<a class='blue' href="activities.php">activities</a><br/>
		<a class='blue' href="subscriptions.php">subscriptions</a></p>
	<?php } ?>
	
 	<h2><a class="pnav" href="blog.php">BLOG</a></h2>
 	<?php if ($section=="blog")
	{ ?>
		<p style='border-right:1px solid #ccc; margin-right:5px;  padding-right:2px;'><a class='blue' href="posts.php">posts</a><br/>
		<a class='blue' href="comments.php">comments</a><br/>
		<a class='blue' href="blog_settings.php">settings</a><br/>
		<a class='blue' href="blog_skin.php">skin</a></p>
	<?php } ?>
	
	
	<h2><a class="pnav" href="messages.php">MESSAGES</a></h2>
	<?php if ($section=="messages")
	{ ?>
		<p style='border-right:1px solid #ccc; margin-right:5px; padding-right:2px;'><a class='blue' href="compose.php">new message</a><br/>
		<a class='blue' href="sent.php">sent messages</a><br/>
		<a class='blue' href="messages.php">inbox</a></p>
	<?php } ?>

 	<h2><a class="pnav" href="contribute.php">CONTRIBUTE</a></h2>
   	