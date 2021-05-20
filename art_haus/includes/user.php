<?php
require_once(dirname(__FILE__) . '/load.php');

if(!class_exists('users')){
	class users {
		
		public function getUsername(){
			if(isset($_COOKIE['essielogauth'])){
				$cookie = $_COOKIE['essielogauth'];
	
				//Set our user and authID variables
				$user = $cookie['user'];
				return $user;
			}
			
			
		}
		
		public function get_user_id(){
			if(isset($_COOKIE['essielogauth'])){
				$cookie = $_COOKIE['essielogauth'];
				global $essieDB;
	
				//Set our user and authID variables
				$user = $cookie['user'];
				$query = "SELECT * FROM user_profile WHERE username = '" . $user . "'";
				$result = $essieDB->select($query);
				if(!$result){
					die("that user does not exist");
				}
				$result = mysql_fetch_assoc($result);
				$user_id = $result['user_id'];
				
				return $user_id;
			}
			
			
		}
		
		public function get_another_username($user_id){
			global $essieDB;
			$query = "SELECT username FROM user_profile WHERE user_id = '" . $user_id . "'";
			$result = $essieDB->select($query);
			if(!$result){
				die("something is wrong, no user id matches" . mysql_error());
			}
			$result = mysql_fetch_assoc($result);
			$username = $result['username'];
			return $username;
		}
	}
}

$u = new users;
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
</body>
</html>