<?php
require_once(dirname(__FILE__) . '/includes/load.php');
?>

<?php

if(isset($_POST['uid'])){
	global $essieDB;
	//retrieve user id and status from client
	$user_id = $_POST['uid'];
	$status = $_POST['stat'];
	$time = date("Y-m-d H:i:s");
	$table = 'status';
	$fields = array('user_id', 'status_time', 'status_content');
	$values = array(
		'user_id'=> $user_id,
		'status_time'=> $time,
		'status_content' =>$status			
	);
	$cleanValues = $essieDB->clean($values);
	//insert values into database table
	$insert = $essieDB->insert($link, $table, $fields, $cleanValues);
	
	if($insert==true){
		
		
		//now display table to client
		$query = "SELECT status_time,status_content FROM status WHERE user_id = '" . 			$user_id . "' ORDER BY id DESC";
		$result = $essieDB->select($query);
		$num_rows = mysql_num_rows($result);
		$row = mysql_fetch_assoc($result); //just the first row
		if(!$result){
			die("unfortunately the status table cannot be loaded" . mysql_error());
		}
	
		print "<table id='status_table' border='1'><caption> <h2> statuses </h2> </caption>";
		print "<tr align = 'center'>";

		// Get the number of rows in the result, as well as the first row
		//  and the number of fields in the rows

	 
		if ($num_rows == 0):
		    print "no statuses";
		    exit;
		endif;


	
	
		
		while($row = mysql_fetch_assoc($result)){
			print "<tr align = 'center'>";
			print "<td>";
			print $row['status_time'];
			print "</td>";
			print "<td>";
			print $row['status_content'];
			print "</td>";
			print "</tr>";
		}
		
		
		
		
	}else{
		print "there was an error, status not posted!";
	}
	
	

	
	
	 
	
	
}
else{
	print "No can do kid";
}
?>