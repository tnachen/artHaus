<?php
require_once(dirname(__FILE__) . '/includes/load.php');

if(isset($_GET['action'])){
	if ( $_GET['action'] == 'logout' ) {
		$loggedout = $essie->logout();
	}
}


$logged = $essie->login('profile.php');


?>


<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		
		<title>artHaus Login</title>
	</head>
	<body>
		<div>
			<?php if ( $logged == 'invalid' ) : ?>
				<p style="background: #e49a9a; border: 1px solid #c05555; padding: 7px 10px;">
					The username password combination you entered is incorrect. Please try again.
				</p>
			<?php endif; ?>
			
			<?php if(isset($_GET['reg'])){
				if ( $_GET['reg'] == 'true' ) { ?>
		<p style="background: #e49a9a; border: 1px solid #c05555; padding: 7px 10px;">
			Your registration was successful, please login below.
		</p>
					<?php }
			} ?>
			
				<?php if(isset($_GET['action'])){
					if ( $_GET['action'] == 'logout' ) { ?>
			<p style="background: #e49a9a; border: 1px solid #c05555; padding: 7px 10px;">
				You have been successfully logged out.
			</p>
						<?php }
				else{ ?>
					<p style="background: #e49a9a; border: 1px solid #c05555; padding: 7px 10px;">
						There was a problem logging you out.
					</p>
					
					<?php } }?>
			
				<?php if(isset($_GET['msg'])){
					if ( $_GET['msg'] == 'login' ) { ?>
			<p style="background: #e49a9a; border: 1px solid #c05555; padding: 7px 10px;">
				You must log in to view this content. Please log in below.
			</p>
						<?php }
				} ?>
				
		
	<h2> artHaus </h2><p>
This is a site devoted to perpetuating creativity. <br />
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<div id="input_section">
			<table>
			<tr><td>Username:</td> <td> <input id="username" name="username" type="text"/> </td></tr>
			<tr><td>Password:</td> <td><input id="password" name="password" type="password"></td></tr>
			<tr><td><input type="submit" value="Log in!" id="login_submit" name="login_submit"/></td></tr>
		</table>
			
		</div>
	</form>
	
	</body>
</html>
