<?php
	
if(!class_exists('cs4750eaq2gfDB')){
	
	class cs4750eaq2gfDB {
		
		
		//magic constructor
		function cs4750eaq2gfDB(){
			
			return $this->_construct();
			
		}
		
		
		function _construct(){
			
			
			return $this->connect();
		}
		
		function connect(){
			
			$link = mysql_connect('localhost', DB_USER, DB_PASS);

			if (!$link) {
				die('Could not connect: ' . mysql_error());
			}

			$db_selected = mysql_select_db(DB_NAME);

			if (!$db_selected) {
				die('Can\'t use ' . DB_NAME . ': ' . mysql_error());
			}
			
		}
		
		//runs through all user input from POST method and cleans it up...escaping html, etc. 
		function clean($array){
			
			return array_map('mysql_real_escape_string', $array);
			
		}
		
		function hash_password($password, $nonce){
			
			$secureHash = hash_hmac('sha512', $password . $nonce, SITE_KEY);
			return $secureHash;
		}
		
		//inserting into a table
		//$link is the mySql resource link, table is the table we're inserting into, $fields are the fields of the table, and $values are the values of the fields.
		//imploding data takes all items in the array and turns them into properly formatted strings
		function insert($link, $table, $fields, $values){
			
			$fields = implode(", ", $fields);
			$values = implode("', '", $values);
			$sql="INSERT INTO $table (id, $fields) VALUES ('', '$values')";

			if (!mysql_query($sql)) {
				die('Error: ' . mysql_error());
				exit;
			} else {
				return TRUE;
			}
		}
		
		function update($query){
			$result = mysql_query($query);
			return $result;
		}
		
		
		function select($sql){
			
		
			$results = mysql_query($sql);
			if(!$results):
				die("Error" . mysql_error());
			endif;
			return $results;
		}
		
	}
}

//Instantiate db class
$essieDB = new cs4750eaq2gfDB;
	
?>