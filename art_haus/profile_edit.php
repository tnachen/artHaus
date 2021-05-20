<?php

	require_once(dirname(__FILE__) . '/includes/load.php');
	require_once(dirname(__FILE__) . '/includes/nav.php');
	global $u;
	global $essieDB;
	
	
	$logged_user_id = $u->get_user_id();
	
	if ( !empty ( $_POST ) ) {
		$update = $insert->update_user($logged_user_id, $_POST);
	}
	
	$user = $query->load_user_object($logged_user_id);
	
	$query = "SELECT * FROM user_profile WHERE user_id='" .$logged_user_id . "'";
	$result = $essieDB->select($query);
	if(!$result){
		die("no user profile found" . mysql_error());
	}
	while($row = mysql_fetch_assoc($result)){
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$email_address = $row['email_address'];
		$dob = $row['dob'];
		$location = $row['location'];
		$username = $row['username'];
	}
	
	$sec_query = "SELECT * FROM user_sec WHERE username='" . $username . "'";
	$sec_result = $essieDB->select($sec_query);
	if(!$sec_result){
		die("error conntecting to user_sec" . mysql_error());
	}
	while($secRow = mysql_fetch_assoc($sec_result)){
		$sec_q = $secRow['sec_q'];
		$sec_ans = $secRow['sec_ans'];
	}


?>
<!DOCTYPE html>
<html>
<head>
	<title>edit your portfolio</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="js/main.js"></script>
	
	
	<script>
	function updatepicture(pic){
		_("image").setAttribute("src", pic);
	}
	</script>
</head>
<body>
	<br/><br/><br/>
	<h2>Oh haiiiii...</h3></br>
		<font style="font-family: Courier" size="2px"> <p>edit your portfolio below...</p>
			<br/><br/>
			<div id="portfolio_edit">
				
				<iframe style="display:none" name="iframe"></iframe>
				<form id="photo_form" action="upload.php" method="post" enctype="multipart/form-data" target="iframe">
					<p id="upload_message">Upload message here</p>
					<img style="min-height:120px; min-width:200px;max-height:120px;" id="image"><br /><br /><br />
					
					choose file: <input type="file" id="profile_pic" name="profie_pic" />
					<input type="submit" name="submit_pic" id="submit_pic" value="upload pic" />
				</form>
					
				
				
				<form id="registration" name="registration" action="register.php" method="POST">
					
					
					
					<div id="user_bio">
						<h3>Who are you?</h3></br>
						
						First Name: <input type="text" id="first_name" name="first_name" value="<?php echo $first_name?>">
						Last Name: <input type="text" id="last_name" name="last_name" value="<?php echo $last_name?>">
						Email: <input type="email" id="email_address" name="email_address" value="<?php echo $email_address?>"></br>
						DOB: <input type="date" id="dob" name="dob" value="<?php echo $dob?>">
						City,Country: <input type="text" id="location" name="location" value="<?php echo $location?>">
						
					</div>
<!---------------------------------------------------------------------------------------->					<hr>
					
					<div id="user_login_info">
						<h3>What do we call you?</h3></br>
						
						Username: <input type="text" id="username" name="username" disabled style="background-color:grey">
						Password: <input type="password" id="password" name="password">
						<input type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'"> Show password
						
						<h4>In case you lose your mind and forget how to get back in here...</h4>
						Security Question: <br/>
						Currently: <?php echo $sec_q ?><br/>
						
						Choose new question:
						<select name="secQ">
							<option value="If you could bring back one dead musician, who would you bring back?">If you could bring back one dead musician, who would you bring back?</option>
							<option value="Who was your biggest inspiration growing up?">Who was your biggest inspiration growing up?</option>
							<option value="You're stranded alone on an island with one special item. What is it?">You're stranded alone on an island with one special item. What is it?</option>
							<option value="What is the first film you remember seeing as a child?">What is the first film you remember seeing as a child?</option>
								
						</select><br/>
						Security Answer: <input type="text" name="secAns" id="secAns">
						</div>
<!-- ---------------------------------------------------------------------------------- -->
						<hr>
					<div id="user_artistry">
						<h3>What are you doing here?</h3></br>
						You are a(n): 
						<select name="i_am">
							<option value="musician">Musician</option>
							<option value="actor">Actor</option>
							<option value="photo">Photographer</option>
							<option value="video">Filmmaker</option>
							<option value="writer">Writer</option>
							<option value="artist">Visual Artist</option>
							<option value="other">Other</option>
						</select><br />
						
					    <a href="#" id="skill_link" onclick="return false" onmousedown="new_skill('skills2','skill_link')">
					           Add a second skill
					         </a><br/>
						<div id="skills2" style="display:none">
							<select name="i_am2">
								<option value="musician">Musician</option>
								<option value="actor">Actor</option>
								<option value="photo">Photographer</option>
								<option value="video">Filmmaker</option>
								<option value="writer">Writer</option>
								<option value="artist">Visual Artist</option>
								<option value="other">Other</option>
							</select><br />
						</div>
						
					    <a href="#" id="skill_link2" onclick="return false" onmousedown="new_skill('skills3','skill_link2')">
					           Add a third skill
					         </a><br/>
	 						<div id="skills3" style="display:none">
	 							<select name="i_am2">
	 								<option value="musician">Musician</option>
	 								<option value="actor">Actor</option>
	 								<option value="photo">Photographer</option>
	 								<option value="video">Filmmaker</option>
	 								<option value="writer">Writer</option>
	 								<option value="artist">Visual Artist</option>
	 								<option value="other">Other</option>
	 							</select><br />
	 						</div>
							
						you're looking to work with a:
						<select id="looking_for">
							<option value="musician">Musician</option>
							<option value="actor">Actor</option>
							<option value="photo">Photographer</option>
							<option value="video">Filmmaker</option>
							<option value="writer">Writer</option>
							<option value="artist">Visual Artist</option>
							<option value="other">Other</option>
						</select>
					</div>
					<br/>
					Time to set up your profile!
					<input type="submit" id="reg_submit" name="reg_submit" value="Done">
					
				</form>
			</div>
		</body>
		</html>
	