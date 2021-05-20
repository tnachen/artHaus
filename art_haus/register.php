<?php

	require_once(dirname(__FILE__) . '/includes/load.php');

	

$essie->register('login.php');



?>
<?php
if(isset($_POST["usernamecheck"])){
	include_once("php_includes/db_conx.php");
	$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
	$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
    $uname_check = mysqli_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 16) {
	    echo '<strong style="color:#F00;">3 - 16 characters please</strong>';
	    exit();
    }
	if (is_numeric($username[0])) {
	    echo '<strong style="color:#F00;">Usernames must begin with a letter</strong>';
	    exit();
    }
    if ($uname_check < 1) {
	    echo '<strong style="color:#009900;">' . $username . ' is OK</strong>';
	    exit();
    } else {
	    echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
	    exit();
    }
}
?>
<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["u"])){
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = $_POST['p'];
	$g = preg_replace('#[^a-z]#', '', $_POST['g']);
	$c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$u_check = mysqli_num_rows($query);
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($u == "" || $e == "" || $p == "" || $g == "" || $c == ""){
		echo "The form submission is missing values.";
        exit();
	} else if ($u_check > 0){ 
        echo "The username you entered is alreay taken";
        exit();
	} else if ($e_check > 0){ 
        echo "That email address is already in use in the system";
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 16) {
        echo "Username must be between 3 and 16 characters";
        exit(); 
    } else if (is_numeric($u[0])) {
        echo 'Username cannot begin with a number';
        exit();
    } else {
	// END FORM DATA ERROR HANDLING
	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt
		$cryptpass = crypt($p);
		include_once ("php_includes/randStrGen.php");
		$p_hash = randStrGen(20)."$cryptpass".randStrGen(20);
		// Add user info into the database table for the main site table
		$sql = "INSERT INTO users (username, email, password, gender, country, ip, signup, lastlogin, notescheck)       
		        VALUES('$u','$e','$p_hash','$g','$c','$ip',now(),now(),now())";
		$query = mysqli_query($db_conx, $sql); 
		$uid = mysqli_insert_id($db_conx);
		// Establish their row in the useroptions table
		$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
		$query = mysqli_query($db_conx, $sql);
		// Create directory(folder) to hold each user's files(pics, MP3s, etc.)
		if (!file_exists("user/$u")) {
			mkdir("user/$u", 0755);
		}
		// Email the user their activation link
		$to = "$e";							 
		$from = "auto_responder@yoursitename.com";
		$subject = 'yoursitename Account Activation';
		$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>yoursitename Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.yoursitename.com"><img src="http://www.yoursitename.com/images/logo.png" width="36" height="30" alt="yoursitename" style="border:none; float:left;"></a>yoursitename Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://www.yoursitename.com/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';
		$headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to, $subject, $message, $headers);
		echo "signup_success";
		exit();
	}
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/ajax.js"></script>
	<script src="js/triggers.js"></script>
	
	
	<style type="text/css">
	#registration{
		margin-top:24px;	
	}
	#registration > div {
		margin-top: 12px;	
	}
	#registration > input,select {
		width: 200px;
		padding: 3px;
		background: #F3F9DD;
	}
	#registration {
		font-size:18px;
		padding: 12px;
	}
	#registration {
		border:#CCC 1px solid;
		
		padding: 12px;
	}
	</style>
	
	<script>
	function restrict(elem){
		var tf = _(elem);
		var rx = new RegExp;
		if(elem == "email_address"){
			rx  = /[' "]/gi;
		}else if(elem=="username"){
			rx = /[^a-z0-9]/gi;
		}
		tf.value = tf.value.replace(rx, "");
	}
	
	
	
 	function checkusername(){
 		var u = _("username").value;
		if(u != ""){
			_("unamestatus").innerHTML = 'checking ... <img src="ajax-loader.gif"'; //could use animated gif here 
				var ajax = ajaxObj("POST", "register.php");
		        ajax.onreadystatechange = function() {
			        if(ajaxReturn(ajax) == true) {
			            _("unamestatus").innerHTML = ajax.responseText;
			        }
		        }
		        ajax.send("usernamecheck="+u);
			}
 	}
	
	function openTerms(){
		_("terms").style.display = "block";
		emptyElement("status");
	}
	
	// function addEvents(){
//
// 		_("elemID").addEventListener("click", func, false);
//
//
// 	}
// 	window.onload = addEvents;
	</script>
	

	
</head>
<body>
	<div id="pageMiddle">
	<h2>Oh haiiiii...</h3></br>
		<font style="font-family: Courier" size="2px"> <p>Welcome to artHaus registration! Fill out the stuff below to move forward and start collaborating!</p>
			
			<div id="registration">
				<form name="registration" action="register.php" method="POST">
					<div id="user_bio">
						<h3>Who are you?</h3></br>
						
						<div>First Name: </div>
							<input type="text" id="first_name" name="first_name" onblur="checkLogin()" maxlength="50" onfocus="emptyElement('status')">
							<span id="first_namestatus"></span>
							
						<div>Last Name: </div>
							<input type="text" id="last_name" name="last_name" onfocus="emptyElement('status')">
							<span id="last_namestatus"></span>
							
							<div>Email: </div>
							<input type="email" id="email_address" name="email_address" onfocus="emptyElement('status')"></br>
					</div>
					<span id="emailstatus"></span>
<!---------------------------------------------------------------------------------------->					<hr>
					
					<div id="user_login_info">
						<h3>What do we call you?</h3></br>
						
						<div>Username:</div> 
						<input type="text" id="username" name="username" onfocus="emptyElement('status')" onkeyup="retrict('username')">
						<span id="unamestatus"></span>
						
						<div>Password:</div> 
						<input type="password" id="password" name="password" onfocus="emptyElement('status')">
						<span id="passwordstatus"></span>
						<div>Confirm Password: </div>
						<input type="password" id="password2" name="password2" onfocus="emptyElement('status')">
						<span id="confirmpasswordstatus"></span>
						
						<div>
						<input type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'">Show Password</div>
						
						<h4>In case you lose your mind and forget how to get back in here...</h4>
						Security Question: 
						<select name="secQ">
							<option value="If you could bring back one dead musician, who would you bring back?">If you could bring back one dead musician, who would you bring back?</option>
							<option value="Who was your biggest inspiration growing up?">Who was your biggest inspiration growing up?</option>
							<option value="You're stranded alone on an island with one special item. What is it?">You're stranded alone on an island with one special item. What is it?</option>
							<option value="What is the first film you remember seeing as a child?">What is the first film you remember seeing as a child?</option>
								
						</select><br/>
						Security Answer: <input type="text" name="secAns" id="secAns">
						</div>

					<br/> 
					<hr>
				    <a href="#" onclick="return false" onmousedown="openTerms()">
				           View the Terms Of Agreement
				         </a>
					<div id="terms" style="display:none;">
						<h3>artHaus terms of agreement</h3>
						<p>1. play nice</p>
						<p>2. follow the flow state</p>
						<p>3. make art</p>
					</div><br /><br />
					Time to set up your profile!
					<input type="submit" id="reg_submit" name="reg_submit" value="Done">
					<span id="status"></span>
					
					
				</form>
			</div>
		</div>
		</body>
		</html>
	