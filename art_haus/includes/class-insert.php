<?php
	require_once(dirname(__FILE__) . '/load.php');
	
	if ( !class_exists('INSERT') ) {
		class INSERT {
			public function update_user($user_id, $postdata) {
				global $essieDB;
				
				$table = 'user_profile';
				$table2 = 'user';
				
				$query = "
								UPDATE $table
								SET first_name='$postdata[first_name]', 								last_name='$postdata[last_name]', dob='$postdata[dob]',location='$postdata[location]', skill1='$postdata[skill1]',skill2='$postdata[skill2]',skill3='$postdata[skill3]'
								WHERE ID=$user_id
							";
				
				$query2 = "
								UPDATE $table
								SET first_name='$postdata[first_name]', 								last_name='$postdata[last_name]'
								WHERE ID=$user_id
							";
							
							$updates = array(
								$essieDB->update($query),
								$essieDB->update($query2)
							);

				return $updates;
			}
			
			public function update_security($user_id, $postdata){
				$table = 'user_sec';
				$query = "
							UPDATE $table
							SET first_name='$postdata[first_name]', 							last_name='$postdata[last_name]'
							WHERE ID=$user_id
					
					";
					return $essieDB->update($query);
			}
			
			public function add_friend($user_id, $friend_id) {
				global $essieDB;
				
				$table = 'mates';
				$mate_time = $_POST['mate_time'];				
				$query = "
								INSERT INTO $table (user1, user2, datemade)
								VALUES ('$user_id', '$friend_id', 								'$mate_time')
							";
				
				return $essieDB->select($query);
			}
			
			public function remove_friend($user_id, $friend_id) {
				global $essieDB;
				$table = 'mates';
				
				$query = "
								DELETE FROM $table 
								WHERE user1 = '$user_id'
								AND user2 = '$friend_id' OR user1 = '$friend_id' AND user2='$user_id'
							";
				
				return $essieDB->select($query);
			}
			
			public function add_status($user_id) { //got rid of $_POST parameter, not needed...$_POST is superglobal, so should be able to access post variables through POST array without passing it in
				global $essieDB;
				
				$table = 'status';
				
				$query = "
								INSERT INTO $table (user_id, status_time, status_content)
								VALUES ($user_id, '$_POST[status_time]', '$_POST[status_content]')
							";
							
				
		   				 $resulter = $essieDB->select($query);
		   				 if($resulter){
		   				 	return 'success';
		   				 }else{
		   					 echo "Error" . mysql_error();
		   					 return 'bad_job';
		   				 }
			}
			
			public function send_message() {
				global $essieDB;
				
				$table = 'messages';
				
				$query = "
								INSERT INTO $table (message_time, sender_id, recipient_id, subject, contents)
								VALUES ('$_POST[message_time]', '$_GET[mid]', '$_GET[uid]', '$_POST[message_subject]', '$_POST[message_content]')
							";
							
				 $resulter = $essieDB->select($query);
				 if($resulter){
				 	return 'success';
				 }else{
					 echo "Error" . mysql_error();
					 return 'bad_job';
				 }
				 
			}
			
			
			
			public function send_forum_message() {
				global $essieDB;
				global $u;
				$user_id = $u->get_user_id();
				$table = 'skl';
				
				$query = "
								INSERT INTO $table (user_id, topic_id, subject, 								msg_txt, date)
								VALUES ($user_id, '$_POST[message_recipient_id]', '$_POST[forum_subject]', '$_POST[forum_content]', '$_POST[message_time]')
							";
							
				 $resulter = $essieDB->select($query);
				 if($resulter){
				 	return 'successes';
				 }else{
					 echo "Error" . mysql_error();
					 return 'bad_jobs';
				 }
				 
			}
		}
	}
	
	$insert = new INSERT;
?>