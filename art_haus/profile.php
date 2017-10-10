<?php



require_once(dirname(__FILE__) . '/includes/load.php');
require_once(dirname(__FILE__) . '/includes/nav.php');
if(!isset($_GET['uid'])){
	global $u;
	$uid = $u->get_user_id();
	echo $uid;
	$redirect = "profile.php?uid=$uid";
	echo $redirect;
	header("Location: $redirect");
	
}

if ( !empty ( $_POST ) ) {
	if(isset($_POST['type'])){
		if ( $_POST['type'] == 'add' ) {
			$add_friend = $insert->add_friend($_POST['user_id'], $_POST['friend_id']);
		}
	
		if ( $_POST['type'] == 'remove' ) {
			$remove_friend = $insert->remove_friend($_POST['user_id'], $_POST['friend_id']);
		}
		
	}
	
}

global $u;
$me = $u->get_user_id();
$logged_user_id = $me;

if ( !empty ( $_GET['uid'] ) ) { // if no user id is passed in request, we just go to our own profile
	
	$user_id = $_GET['uid'];
	$user = $query->load_user_object($user_id);
	$mine = false;
	if ( $logged_user_id == $user_id ) {
		$mine = true;
	}
} else {
	$user_id = $me;
	$user = $query->load_user_object($logged_user_id);
	$mine = true;
}

$friends = $query->get_friends($logged_user_id);

$logged3 = $essie->checkLogin();

if ( $logged3 == false ) {
	//Build our redirect
	$url = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$redirect = str_replace('profile.php', 'login.php', $url);
	//Redirect to the home page
	header("Location: $redirect?msg=login");
	exit;
} else {
	//Grab our authorization cookie array
	$cookie = $_COOKIE['essielogauth'];
	
	//Set our user and authID variables
	$user = $cookie['user'];
	$authID = $cookie['authID'];
	
	//Query the database for the selected user
	$table = 'user_sec';
	$sql = "SELECT * FROM $table WHERE username = '" . $user . "'";
	$results = $essieDB->select($sql);

	//Kill the script if the submitted username doesn't exit
	if (!$results) {
		die('Sorry, that username does not exist dad!' . mysql_error());
	}

	//Fetch our results into an associative array
	$results = mysql_fetch_assoc( $results );
}

//check for form submission

	
	

?>

<!DOCTYPE html>
<html>
<head>
	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="js/main.js"></script>
	<title><?php echo $user . "'s";?> portfolio</title>
	
	
	
</head>
<body>
	
	<div id="user_id" style="display:none"><?php global $u; $me = $u->get_user_id(); echo $me; ?></div>
	<br /><br />
	<br />
	<h2><?php if($mine){echo "your";}else{ global $u; $name = $u->get_another_username($user_id); echo $name . "'s";}?> Portfolio</h2>
	<br />
	
	<!-- profile image lies here -------------------------------->
	<iframe id="profile_pic"></iframe>
	<br /> <br / > <br />
	
	
	
	
	
	<!-- profile navigation lives here ---------------------------------------------->
	<ul class="profile_nav">
		<li class="profile_nav"><a class="profile_nav" href="bio.php?uid=<?php echo $user_id?>">bio</a></li> 
		<li class="profile_nav"><a class="profile_nav" href="/art_haus/projects.php?uid=<?php echo $user_id?>">projects</a></li>
		<li class="profile_nav"><a class="profile_nav" href="/art_haus/mates_directory.php?uid=<?php echo $user_id?>"><?php if(!$mine): global $u; $name = $u->get_another_username($user_id);echo $name . "'s "; else: echo "my "; endif;?>mates</a></li>  
		
		<?php if($mine):?>
			<li class="profile_nav"><a class="profile_nav" 			href="/art_haus/messages_inbox.php?uid=<?php echo $user_id?>">messages</a></li>	
			<li class="profile_nav"><a class="profile_nav" 			href="/art_haus/profile_edit.php?uid=<?php echo $user_id?>">edit portfolio</a></li>
			<?php else: ?>
				<li class="profile_nav"><a class="profile_nav" 				href="/art_haus/messages_compose.php?uid=<?php echo $_GET['uid']?>&mid=<?php echo $logged_user_id?>"> send message</a></li>
					<?php if ( !in_array($user_id, $friends) ) : ?>
							<p>
								<form method="POST">
									<input type="hidden" name="mate_time" value="<?php echo date("Y-m-d H:i:s");?>">
									<input name="user_id" type="hidden" value="<?php echo 									$logged_user_id; ?>" />
									<input name="friend_id" type="hidden" value="<?php 									echo $user_id; ?>" />
									<input name="type" type="hidden" value="add" />
									<li style="list-style-type: none"><a ><input type="submit" value="add as mate" 									/></a></li>
								</form>
							</p>
					<?php else : ?>
							<p>
								<form method="post">
									<input type="hidden" id="remove_time" value="<?php echo time();?>">
									<input name="user_id" type="hidden" value="<?php echo 									$logged_user_id; ?>" />
									<input name="friend_id" type="hidden" value="<?php 									echo $user_id; ?>" />
									<input name="type" type="hidden" value="remove" />
									<li style="list-style-type: none"><a><input type="submit" value="remove mate" 									/></a></li>
								</form>
					<?php endif;?>
		
	<?php endif;?>
		
	</ul>
	
	<br /><br /><br /><br /><br />
	
	<!-- status space------------------------------------------------ -->
	<div id="post_stats">
		<p><?php if($mine):?><a href="feed_post.php" style="float:left">post a status</a></p><br />
		<div id="status_space" style="display:none">
			<form name="status_form" id="status_form" onsubmit="return false;">
			<textarea id="stat_box" class="stat_box" 			style="height:50px;width:260px;float:left"></textarea>
		
		<br /><br />
		<input name="status_time" type="hidden" value="<?php echo time()?>" />
		<input id="submit_status" type="submit" style="float:left" value="post" />
		</div>
			</form>
			<div id="status_status"></div >
<?php endif;?>
	</div>
	<br /> <br /> <br /> <br /> <br />
	
	<div id="get_stats">
		<?php
			
			
		$current_user = $_GET['uid'];	
			
		//gonna try to user query class to post status
		
		$query->do_status_update($current_user);
		
		
		?>
		
		
	</div>
	
	

</body>
</html>
		
<?php 
		?>