<?php
require_once(dirname(__FILE__) . '/includes/load.php');
require_once(dirname(__FILE__) . '/includes/nav.php');
global $u;
$me = $u->get_user_id();
	$logged_user_id = $me;
	global $essieDB;
	
	if ( !empty ( $_POST ) ) {
		$add_status = $insert->add_status($logged_user_id, $essieDB->clean($_POST));
		
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Post Status</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<br /><br /><br /><br />
		<h1>Post Status</h1>
		<div class="content">
			<form method="post">
					<input name="status_time" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>" />
				<p>What's on your mind?</p>
				<textarea name="status_content"></textarea>
				<p>
					<input type="submit" value="Submit" />
				</p>
				
				
				<div id="feed_status">
					
				<?php if(isset($add_status)):if($add_status == 'success'):?>	
					
					<p style="background: #e49a9a; border: 1px solid #c05555; padding: 7px 10px;">
						Your status has been posted!
					</p>
					<?php
				endif;		
				endif;
				
				if(isset($add_status)):if($add_status == 'bad_job'):?>	
					
									<p style="background: #e49a9a; border: 1px solid #c05555; padding: 7px 10px;">
										Your status could not be posted! Please try again later!
									</p>
									<?php
								endif;		
								endif;
					?>
				</div>
			</form>
		</div>
	</body>
</html>