<?php
if(!isset($_SESSION)) { session_start();} 
/* Start session, this is necessary, it must be the first thing in the PHP document after <?php syntax ! */ 

$_SESSION = array();    // Unset all of the session variables.

session_destroy();  // Destroy all session data.
header('Location: index.php');