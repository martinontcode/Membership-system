<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
/* Include header */
include_once 'views/header.php';
if(!isset($_SESSION)) { session_start();}
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    // Verify data
    $email = mysql_real_escape_string($_GET['email']); // Set email variable
    $hash = mysql_real_escape_string($_GET['hash']); // Set hash variable
    
    include('config/DB.php');
    $search = mysql_query("SELECT email, hash, active FROM users WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error()); 
    $match  = mysql_num_rows($search);
 
    if($match > 0){
        // We have a match, activate the account
        mysql_query("UPDATE users SET active='1' WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error());
        $regmsg = '<div class="statusmsg">Your account has been activated, you can now log in</div>';
    }else{
        // No match -> invalid url or account has already been activated.
        $regmsg = '<div class="statusmsg">The URL is either invalid or your account has already been activated</div>';
    }
                 
    }else{
    // Invalid approach
    $regmsg = '<div class="statusmsg">Invalid method, click on the link sent to your email.</div>';
    }
?>
<div class="container">

    <!-- Activated msg -->
    <div class="c-content-title-1">
     	<?php echo $regmsg; ?></h3>
    </div>
   <!-- End Activated msg-->
 


<!-- Back to main page -->  
<div class="cnt gray"><a href="index.php">Back to main page</a></div>  
  
</div>
<!-- End div -->
