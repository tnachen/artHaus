<?php
require_once(dirname(__FILE__) . '/includes/load.php');
require_once(dirname(__FILE__) . '/includes/nav.php');
global $u;
$me = $u->get_user_id();
$logged_user_id = $me;
global $essieDB;
	
	if ( !empty ( $_POST ) ) {
		$send_message = $insert->send_forum_message($_POST);
	}
	
	$query = "SELECT * FROM topics";
	$result = $essieDB->select($query);
	
	
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>post to artSKL</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<br/><br/>
		<h1>post to artSKL</h1>
		<div class="content">
			<form method="post">
				<input name="message_time" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>" />
				<input name="message_sender_id" type="hidden" value="<?php echo $logged_user_id; ?>" />
				<p>
					<label for="message_recipient_id">Choose a topic:</label>
					<select name="message_recipient_id">
						<option value="">--select a topic--</option>
						<?php 
						
						while($row = mysql_fetch_assoc($result)):
							echo("<option value=" .$row['topic_id'] . "\'>" . $row['topic'] . "</option>");
						
						
							 endwhile; ?>
					</select>
				</p>
				<p>
					<label class="labels" for="message_subject">Subject:</label>
					<input name="forum_subject" type="text" />
				</p>
				<p>
					<label for="message_content">Message:</label><br/>
					<textarea name="forum_content" style="width:300px;height:300px"></textarea>
				</p>
				<p>
					<input type="submit" value="Submit" />
				</p>
				
				<div id="forum_message">
					
					<?php if(isset($send_message)) :if ( $send_message == 'successes' ) : ?>
						<p style="background: #e49a9a; border: 1px solid #c05555; padding: 7px 10px;">
							Your message has been posted!
						</p>
					<?php endif; endif; ?>
				</div>
			</form>
		</div>
	</body>
</html>