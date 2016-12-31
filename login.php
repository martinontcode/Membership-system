<?php 
if(!isset($_SESSION)) { session_start();} 
/* Start session, this is necessary, it must be the first thing in the PHP document after <?php syntax ! */ 

/* Require login.php to call login function */
require("classes/classLogin.php");

/* Call for login function */
$login = new Login();

if($login->isLoggedIn() == true){
  header('Location: index.php');    // If user is already logged in redirect back to index.php
} else {
  include("views/loginForm.php");   // Else prompt login form
}