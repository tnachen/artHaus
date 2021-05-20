<?php
// Our main class
if(!class_exists('cs4750eaq2gf')){
	class cs4750eaq2gf {
		
		function register($redirect) {
			global $essieDB;
			//Check to make sure the form submission is coming from our script
			//The full URL of our registration page
			$current = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			//The full URL of the page the form was submitted from
			$referrer = $_SERVER['SCRIPT_FILENAME'];


			if ( !empty ( $_POST ) ) {

				/* 
				 * Here we actually run the check to see if the form was submitted from our
				 * site. Since our registration from submits to itself, this is pretty easy. If
				 * the form submission didn't come from the register.php page on our server,
				 * we don't allow the data through.
				 */
				if ( $current == $current ) {
				
					require_once('db.php');
						
					//Set up the variables we'll need to pass to our insert method
					//This is the name of the table we want to insert data into
					$table = 'user';
					$table2 = 'user_sec';
					$table3 = 'user_profile';
					
					//These are the fields in that table that we want to insert data into
					$fields = array('username','first_name', 'last_name', 'email_address','date_registered');
					$fields2 = array('username', 'password', 'sec_q', 'sec_ans');
					
					//These are the values from our registration form... cleaned using our clean method
					$values = $essieDB->clean($_POST);
					
					//Now, we're breaking apart our $_POST array, so we can store our password securely
					$first_name = $_POST['first_name'];
					$last_name = $_POST['last_name'];
					$username = $_POST['username'];
					$password = $_POST['password'];
					$email_address = $_POST['email_address'];
					$date_registered = date("Y-m-d H:i:s");
					$secAns = $_POST['secAns'];
					$secQ = $_POST['secQ'];
					?>
					
					<html>
					<head>
						<script src="js/main.js"></script>
						<script src="js/ajax.js"></script>
						<script src="triggers.js"></script>
						<!-- client side field checking -->
						
						<script>
						var u = _("username").value;
							var e = _("email_address").value;
							var p1 = _("password").value;
							var p2 = _("password2").value;
							var status = _("status");
							if(u == "" || e == "" || p1 == "" || p2 == ""){
								status.innerHTML = "Fill out all of the form data";
							} else if(p1 != p2){
								status.innerHTML = "Your password fields do not match";
							} else if( _("terms").style.display == "none"){
								status.innerHTML = "Please view the terms of use";
							} else {
								_("submit").style.display = "none";
								status.innerHTML = 'please wait ...';
								var ajax = ajaxObj("POST", "register.php");
						        ajax.onreadystatechange = function() {
							        if(ajaxReturn(ajax) == true) {
							            if(ajax.responseText != "register_success"){
											status.innerHTML = ajax.responseText;
											_("submit").style.display = "block";
										} else {
											window.scrollTo(0,0);
											_("registration").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
										}
							        }
						        }
						        ajax.send("u="+u+"&e="+e+"&p="+p1);
							}
						</script>
					</head>
					<body>
					</body>
					</html>
					
					
					<?php
					
					
					
					//We create a NONCE using the action, username, timestamp, and the NONCE SALT
					$nonce = md5('registration-' . $username . $email_address . NONCE_SALT);
					
					//We hash our password
					$password = $essieDB->hash_password($password, $nonce);
					
					//Recompile our $value array to insert into the database
					$values = array(
								'username' => $username,
								'first_name' => $first_name,
								'last_name' => $last_name,
								'email_address' => $email_address,
								'date_registered' => $date_registered
								
							);
							
							$values2 = array(
								'username' => $username,
								'password' => $password,
								'sec_q' => $secQ,
								'sec_ans' => $secAns
							);
							
							
							
							
							$cleanValues = $essieDB->clean($values);
							$cleanValues2 = $essieDB->clean($values2);
					//And, we insert our data
					$insert = $essieDB->insert($link, $table, $fields, $cleanValues);
					$insert2 = $essieDB->insert($link, $table2, $fields2, $cleanValues2);
					
					$query3 = "SELECT id FROM $table WHERE username = '" . $username . "'";
					$results3 = $essieDB->select($query3);
					if (!$results3) {
						die('Cannot find user id for user ' . $username);
					}
					$results3 = mysql_fetch_assoc( $results3 );
					$user_id = $results3['id'];
					
					$fields3 = array('user_id', 'username', 'first_name', 'last_name', 'email_address', 'date_registered');
					$values3 = array(
						'user_id'=> $user_id,
						'username'=> $username,
						'first_name'=> $first_name,
						'last_name'=> $last_name,
						'email_address'=>$email_address,
						'date_registered'=> $date_registered
						
						
					);
					$cleanValues3 = $essieDB->clean($values3);
					//insert into user_profile table
					$insert3 = $essieDB->insert($link, $table3,$fields3, $cleanValues3 );
					
					if ( $insert == TRUE ) {
						$url = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
						$aredirect = str_replace('register.php', $redirect, $url);
						
						header("Location: $redirect?reg=true");
						exit;
					}
				} else {
					die('Your form submission did not come from the correct page. Please check with the site administrator.');
				}
			}
		}
		
		function login($redirect) {
			global $essieDB;
			if ( !empty ( $_POST ) ) {
				
				//Clean our form data
				$values = $essieDB->clean($_POST);

				//The username and password submitted by the user
				$subname = $values['username'];
				$subpass = $values['password'];

				//The name of the table we want to select data from
				$table = 'user_sec';
				$table2 = 'user';

				/*
				 * Run our query to get all data from the users table where the user 
				 * login matches the submitted login.
				 */
				$sql = "SELECT * FROM $table WHERE username = '" . $subname . "'";
				$results = $essieDB->select($sql);
				$sql2 = "SELECT * FROM $table2 WHERE username = '" . $subname . "'";
				$results2 = $essieDB->select($sql2);

				//Kill the script if the submitted username doesn't exit
				if (!$results) {
					die('Sorry, that username does not exist in table user_sec');
				}
				if(!$results2){
					die('Sorry, that username does not exist in table user');
				}

				//Fetch our results into an associative array
				$results = mysql_fetch_assoc( $results );
				$results2 = mysql_fetch_assoc($results2);
				
				//The registration date of the stored matching user
				$email_address = $results2['email_address'];

				//The hashed password of the stored matching user
				$stopass = $results['password'];

				//Recreate our NONCE used at registration
				$nonce = md5('registration-' . $subname . $email_address . NONCE_SALT);
				
				//Rehash the submitted password to see if it matches the stored hash
				$subpass = $essieDB->hash_password($subpass, $nonce);

				//Check to see if the submitted password matches the stored password
				if ( $subpass == $stopass ) {
					$last_login = date("Y-m-d H:i:s");
					
					//store login time  to lastlogin in user_profile
					$query = "UPDATE user_profile SET lastlogin = '" . $last_login . "' WHERE username='" . $subname . "'";
					$result = $essieDB->select($query);
					if(!$result){
						die("last login cannot be set" . mysql_error());
					}
					//If there's a match, we rehash password to store in a cookie
					$authnonce = md5('cookie-' . $subname . $email_address . AUTH_SALT);
					$authID = $essieDB->hash_password($subpass, $authnonce);
					
					//Set our authorization cookie
					setcookie('essielogauth[user]', $subname, 0, '', '', '', true);
					setcookie('essielogauth[authID]', $authID, 0, '', '', '', true);
					
					
					//Build our redirect
					$url = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
					$redirect = str_replace('login.php', $redirect, $url);
					
					//Redirect to the home page
					header("Location: $redirect");
					exit;	
				} else {
					return 'invalid';
				}
			} else {
				return 'empty';
			}
		}
		
		function logout() {
			//Expire our auth coookie to log the user out
			$idout = setcookie('essielogauth[authID]', '', -3600, '', '', '', true);
			$userout = setcookie('essielogauth[user]', '', -3600, '', '', '', true);
			
			if ( $idout == true && $userout == true ) {
				return true;
			} else {
				return false;
			}
		}
		
		function checkLogin() {
			global $essieDB;
		
			//Grab our authorization cookie array
			$cookie = $_COOKIE['essielogauth'];
			
			//Set our user and authID variables
			$user = $cookie['user'];
			$authID = $cookie['authID'];
			
			/*
			 * If the cookie values are empty, we redirect to login right away;
			 * otherwise, we run the login check.
			 */
			if ( !empty ( $cookie ) ) {
				
				//Query the database for the selected user
				$table = 'user_sec';
				$table2 = 'user';
				$sql = "SELECT * FROM $table WHERE username = '" . $user . "'";
				$sql2 = "SELECT * FROM $table2 WHERE username = '" . $user . "'";
				$results = $essieDB->select($sql);
				$results2 = $essieDB->select($sql2);

				//Kill the script if the submitted username doesn't exit
				if (!$results) {
					die('Sorry, that username does not exist! mom' . mysql_error());
				}

				//Fetch our results into an associative array
				$results = mysql_fetch_assoc( $results );
				$results2 = mysql_fetch_assoc( $results2 );
		
				//The registration date of the stored matching user
				$email_address = $results2['email_address'];

				//The hashed password of the stored matching user
				$stopass = $results['password'];

				//Rehash password to see if it matches the value stored in the cookie
				$authnonce = md5('cookie-' . $user . $email_address . AUTH_SALT);
				$stopass = $essieDB->hash_password($stopass, $authnonce);
				
				if ( $stopass == $authID ) {
					$results = true;
				} else {
					$results = false;
				}
			} else {
				//Build our redirect
				$url = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
				$redirect = str_replace('profile.php', 'login.php', $url);
				
				//Redirect to the home page
				header("Location: $redirect?msg=login");
				exit;
			}
			
			return $results;
		}
		
		function rehash_passwords($table){
			global $essieDB;
			$query = "SELECT username,password,email_address from" . $table ."";
			$result = $essieDB->select($query);
			if(!$result){
				die("error selecting nonce stuff from " . $table . " " . mysql_erro() . 				"<br />");
			}
			
			while($tableRow = mysql_fetch_assoc($result)){
				
				
				$username = $tableRow['username'];
				$password = $tableRow['password'];
				$email_address = $tableRow['email_address'];
				$nonce = md5('registration-' . $username . $email_address . NONCE_SALT);
				$password = $essieDB->hash_password($password, $nonce);
				$update = "UPDATE user_sec SET password= '" . $password . "' WHERE 				username= '" . $username . "' ";
				$result2 = $essieDB->update($update);
				if(!$result2){
					die("the update script failed, passwords not hashed" . mysql_error());
				}else{
					echo "should be a success";
				}
				
				
				
			}
		}
	}
}

//Instantiate our database class
$essie = new cs4750eaq2gf;
?>