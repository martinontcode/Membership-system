<?php 
if(!isset($_SESSION)) { session_start();} 
/* Start session, this is necessary, it must be the first thing in the PHP document after <?php syntax ! */ 

/* Require registration.php to call registration class */
require_once("classes/UserClass.php");

/* Call for registration class */
$registration = new UserClass();

include("views/registrationForm.php");