<?php
require_once("includes/load.php");
require_once("includes/nav.php");
	
global $u;
$me = $u->get_user_id();
	$logged_user_id = $me;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>artHaus</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<br /><br /><br /><br /><br />
		<h1>what's new?</h1>
		<br /><br />
			<div id="post_stats">
				<p><a href="feed_post.php" style="float:left">post a status</a></p><br />
				<div id="status_space" style="display:none">
					<form name="status_form" id="status_form" onsubmit="return false;">
					<textarea id="stat_box" class="stat_box" 			style="height:50px;width:260px;float:left"></textarea>
		
				<br /><br />
				<input name="status_time" type="hidden" value="<?php echo time()?>" />
				<input id="submit_status" type="submit" style="float:left" value="post" />
				</div>
					</form>
					<div id="status_status"></div >
		
			</div>
			<br /><br />
		<div class="content">
			<?php $query->do_news_feed($logged_user_id); ?>
		</div>
	</body>
</html>