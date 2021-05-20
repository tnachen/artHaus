<?php
require_once("includes/load.php");
require_once("includes/nav.php");
global $u;
$user_to_beg = $_GET['uid'];
$friends = $query->get_friends($user_to_beg);
	
?>

<!DOCTYPE>
<html>
<head>
	<title>mates Directory</title>
</head>
<body>
	<br /><br /><br /><br />
	<h1>mates Directory</h1>
	<div class="content">
		<?php $query->do_friends_list($friends);?>
	</div>
</body>
</html>