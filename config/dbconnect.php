<?php 

/* Credentials */
$servername = "Insert your servername";
$username = "Insert your database user";
$password = "Insert your database password";
$database = "Insert your database name";


/* Connection */
$conn = new mysqli($servername, $username, $password, $database);

/* If connection fails for some reason */
if ($conn->connect_error) {
	die("Database connection failed: ". $conn->connect_error);
}

?>