<?php
	
require_once(dirname(__FILE__) . '/includes/load.php');
require_once(dirname(__FILE__) . '/includes/nav.php');
?>

<!DOCTYPE html>
<html>
<head>
	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="js/main.js"></script>
	<title>artSKL</title>
</head>
<body>
	
	<br/><br/><br/>
	
	<li style="list-style-type: none"><a href="/art_haus/forum_compose.php?uid=<?php echo $logged_user_id?>"> start a discussion</a></li>
	
	<br/><br/><br/>
	<div id="messages">
		<?php $query->get_forum_objects();?>
	</div>
</body>
</head>


