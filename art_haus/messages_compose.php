<?php
require_once(dirname(__FILE__) . '/includes/load.php');
require_once(dirname(__FILE__) . '/includes/nav.php');
global $u;
global $essieDB;
$me = $u->get_user_id();
$logged_user_id = $me;
$friend_name = $u->get_another_username($_GET['uid']);

	// call to send message function
	if ( !empty ( $_POST ) ) {
		$send_message = $insert->send_message($_POST);
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Compose Message</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		
		<br/><br/>
		<h1>write to <?php echo $friend_name;?></h1>
		<div class="content">
			<form method="post">
				<input name="message_time" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>" />
				

				<p>
					<label class="labels" for="message_subject">Subject:</label>
					<input name="message_subject" type="text" />
				</p>
				<p>
					<label for="message_content">Message:</label>
					<textarea name="message_content"></textarea>
				</p>
				<p>
					<input type="submit" value="Submit" />
				</p>
				
				<div id="msg_status">
					<!-- show that message has been sent -->
					<?php if (isset($send_message)): if ( $send_message == 'success' ) : ?>
						<p style="background: #e49a9a; border: 1px solid #c05555; padding: 7px 10px;">
							Your message has been sent!
							
						<?php endif;endif;?>
				</div>
			</form>
		</div>
	</body>
</html>