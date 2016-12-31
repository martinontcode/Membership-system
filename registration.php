<?php 
if(!isset($_SESSION)) { session_start();} 
/* Start session, this is necessary, it must be the first thing in the PHP document after <?php syntax ! */ 

/* Require registration.php to call registration class */
require_once("classes/classRegistration.php");

/* Call for registration class */
$registration = new Registration();

include("views/registrationForm.php");