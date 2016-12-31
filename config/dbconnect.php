<?php 

/* Credentials */
$servername = "Insert your servername";
$username = "Insert your database user";
$password = "Insert your database password";
$database = "login";

/* Connection */
$conn = mysqli_connect($servername, $username, $password, $database);

/* If connection fails for some reason */
if (!$conn) {
	die("Database connection failed: ".mysqli_connect_error());
}

?>
