<?php
require_once(dirname(__FILE__) . '/includes/load.php');
require_once(dirname(__FILE__) . '/includes/nav.php');
global $u;
$me = $u->get_user_id();
$logged_user_id = $me;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Inbox</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		
		<h1>Inbox</h1>
		<div class="content">
			<?php $query->do_inbox($logged_user_id); ?>
		</div>
	</body>
</html>