<?php 


echo("You've made it past the easy log in!");
// plato.cs.virginia.edu/~eaq2gf is where i'll see my website when on the labunix server
// $dbuser = "cs4750eaq2gf";
$dbuser = "root";
// $dbpass = "fall2015";
$dbpass = "";
// $host = "stardock.cs.virginia.edu"
$host = "localhost";
$success = TRUE;
$username = $_POST['username'];
$password = $_POST['password'];

print_r($_POST);


echo("Your user name is " . $username);

$db = new mysqli($host, $dbuser, $dbpass, "cs4750eaq2gf");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }
  
  
  
  ?>