<?php
require_once("includes/load.php");
require_once("includes/nav.php");

	
?>

<!DOCTYPE>
<html>
<head>
	<title>artistSearch</title>
</head>
<body>
	<br /><br /><br /><br />
	<h1>artistSearch</h1>
	<div id="search">
		<input id="aSearch" type="text">
		
	</div>
	<div class="content">
		<?php $query->do_user_directory();?>
	</div>
</body>
</html>