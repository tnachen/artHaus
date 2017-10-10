<?php
	
	require_once("includes/load.php");
	
	global $u;
	$me = $u->get_user_id();
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<ul class="nav">
		<li class="nav"><a class="nav" href="/art_haus/artHaus.php?uid=<?php echo $me?>">artHaus</a></li> <!-- news feed -->
		<li class="nav"><a class="nav" href="/art_haus/profile.php?uid=<?php echo $me?>">portfolio</a></li> <!-- timeline -->
		<li class="nav"><a class="nav" href="/art_haus/artSKL.php?uid=<?php echo $me?>">artSKL</a></li>  <!-- forum -->
		<li class="nav"><a class="nav" href="/art_haus/artistSearch.php?uid=<?php echo $me?>">artistSearch</a></li>  <!-- members area, perhaps use solr for this part -->
		<li class="nav"><a class="nav" href="login.php?action=logout">logout</a></li>
	</ul>
</body>
</html>
