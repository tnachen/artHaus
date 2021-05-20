<?php
	require_once('load.php');	

	if ( !class_exists('QUERY') ) {
		class QUERY {
			public function load_user_object($user_id) {
				global $essieDB;
				
				$table = 'user_profile';
				$query = "
								SELECT * FROM $table
								WHERE user_id = $user_id
							";
				
				$obj = $essieDB->select($query);
				
				if ( !$obj ) {
					return "No user found";
				}
				
				return $obj[0];
			}
			
			public function load_all_user_objects() {
				global $essieDB;
				
				$table = 'user_profile';
				
				$query = "
								SELECT * FROM $table
							";
				
				$obj = $essieDB->select($query);
				
				if ( !$obj ) {
					return "No user found";
				}
				
				return $obj;
			}
			
			public function get_friends($user_id) {
				global $essieDB;
				$friend_ids = array();
				$table = 'mates';
				
				$query = "
								SELECT * FROM $table
								WHERE user1 = '$user_id' OR user2 = '$user_id'
							";
				
				$result = $essieDB->select($query);
				// $friends = mysql_fetch_assoc($result);
				
				while($row = mysql_fetch_assoc($result)){
					if($row['user1']==$user_id){
						array_push($friend_ids, $row['user2']);
						
					}else if($row['user2']==$user_id){
						array_push($friend_ids, $row['user1']);
					}
					
				}
			return $friend_ids;	
			}
			
			public function get_status_objects($user_id) {
				global $essieDB;
				
				$table = 'status';
				
				$friend_ids = $this->get_friends($user_id);
				
				if ( !empty ( $friend_ids ) ) {
					array_push($friend_ids, $user_id);
				} else {
					$friend_ids = array($user_id);
				}
				
				$accepted_ids = implode(', ', $friend_ids);
				
				$query = "
								SELECT * FROM $table
								WHERE user_id IN ($accepted_ids)
								ORDER BY status_time DESC
							";
				
				$status_objects = $essieDB->select($query);
				
				
				return $status_objects;
			}
			
			public function get_message_objects($user_id) {
				global $essieDB;
				
				$table = 'messages';
				
				$query = "
								SELECT * FROM $table
								WHERE recipient_id = '$user_id' OR sender_id = '$user_id'
							";
				
				$messages = $essieDB->select($query);
								
				return $messages;
			}
			
			
			public function get_forum_objects() {
				global $essieDB;
				
				$table = 'skl';
				
				$query = "
								SELECT * FROM $table
							";
				
				$messages = $essieDB->select($query);
				echo "<table border='1'>";
				echo "<tr aligh='center'><th>user</th><th>topic</th><th>subject</th><th>message</th><th>date</th></tr>";
				while($row=mysql_fetch_assoc($messages)){
					echo "<tr><td>";
						echo $row['user_id'];
						echo"</td>";
						echo "<td>";
						echo $row['topic_id'];
						echo"</td>";
						echo "<td>";
						echo $row['subject'];
						echo "</td>";
						echo "<td>";
						echo $row['msg_txt'];
						echo "</td>";
						echo "<td>";
						echo $row['date'];
						echo "</td>";
							echo "</tr>";
				}
						
				echo "</table";				
				return $messages;
			}
			
			
			// help
			public function do_user_directory() {
				$users = $this->load_all_user_objects();
				
				while($row = mysql_fetch_assoc($users)){?>
					<div class="directory_item">
						<h3><a href="/art_haus/profile.php?uid=<?php echo $row['user_id']; ?>"><?php echo $row['username']; ?></a></h3>
						<p><?php echo $row['email_address']; ?></p>
					</div>
					
					<?php
					
				}
				
				
			}
			
			// help
			public function do_friends_list($friends_array) {
				global $essieDB;
				global $u;
				$current_user = $u->get_user_id();
				if(is_array($friends_array)||is_object($friends_array)){
					foreach ( $friends_array as $friend_id ) {
						$query = "SELECT * FROM mates WHERE user2='" . $friend_id . " ' AND user1='" . $current_user . "' OR user1='" . $friend_id . "' AND user2='" . $current_user . "'";
						$result = $essieDB->select($query);
						if(!$result){
							die("friends list cannot be generated " . mysql_error());
						}
						while($row=mysql_fetch_assoc($result)){
							$user2 = $row['user2'];
							$user1 = $row['user1'];
							if($user2 == $friend_id){
								$query2 = "SELECT * FROM user_profile WHERE user_id='" . $user2 ."'";
								$result2 = $essieDB->select($query2);
								if(!$result2){
									die("well querying the user profile didn't go well..." . mysql_error());
								}
								while($row2=mysql_fetch_assoc($result2)){
									?>
									<div class="directory_item">
								<h3><a href="/art_haus/profile.php?uid=<?php echo $row2['user_id']; 						?>"><?php echo $row2['username']; ?></a></h3>
									<p><?php echo $row2['email_address']; ?></p>
										</div>
											<?php
								
								}
								
							}else if($user1==$friend_id){
								$query2 = "SELECT * FROM user_profile WHERE user_id='" . $user1 ."'";
								$result2 = $essieDB->select($query2);
								if(!$result2){
									die("well querying the user profile didn't go well..." . mysql_error());
								}
								while($row2=mysql_fetch_assoc($result2)){
									?>
									<div class="directory_item">
								<h3><a href="/art_haus/profile.php?uid=<?php echo $row2['user_id']; 						?>"><?php echo $row2['username']; ?></a></h3>
									<p><?php echo $row2['email_address']; ?></p>
										</div>
											<?php
								
								}
								
							}
							
							
									
						}
						
									
					}
				}
				
					
				
			}
			
			public function do_news_feed($user_id) {
				$status_objects = $this->get_status_objects($user_id);
				?>
				<table id="news_feed_table" border="1">
				<?php
				
				while($row = mysql_fetch_assoc($status_objects)){
					$status_content = $row['status_content'];
					$status_time = $row['status_time'];
					$user_id = $row['user_id'];
					global $u;
					$username = $u->get_another_username($user_id);
					?>
					<tr align="center"><td>
							<div class="status_item">
								<h3><a href="/art_haus/profile.php?uid=<?php echo $user_id; ?>"><?php echo $username; ?></a></h3></td>
								<td><p><?php echo $status_content; ?></p></td>
								<td><p><?php echo $status_time; ?></p></td></tr>
							</div>
						<?php
	
}

?>
</table>
<?php
								
			}
			
			public function do_status_update($user_id) {
				global $essieDB;
				$query = "SELECT * FROM status WHERE user_id='" . $user_id . "'";
				$status_objects = $essieDB->select($query);
				?>
				<table id="news_feed_table" border="1">
				<?php
				
				while($row = mysql_fetch_assoc($status_objects)){
					$status_content = $row['status_content'];
					$status_time = $row['status_time'];
					global $u;
					$username = $u->get_another_username($user_id);
					?>
					<tr align="center"><td>
							<div class="status_item">
								<h3><a href="/art_haus/profile.php?uid=<?php echo $user_id; ?>"><?php echo $username; ?></a></h3></td>
								<td><p><?php echo $status_content; ?></p></td>
								<td><p><?php echo $status_time; ?></p></td></tr>
							</div>
						<?php
	
}

?>
</table>
<?php
				
			}
			
			public function do_inbox($user_id) {
				global $essieDB;
				$message_objects = $this->get_message_objects($user_id);
				while($row=mysql_fetch_assoc($message_objects)){
					
					echo $row['contents'];
				}
				
				
				
			}
		}
	}
	
	$query = new QUERY;
?>