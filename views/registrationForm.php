<?php
include_once 'header.php';
/* Include header */
?>
<div class="container">

    <!-- Registration form -->
    <div class="registrationForm">
        <form  action="registration.php" name="registrationform" class="form-registration" method="post">
            <h3 class="cnt">Sign up!</h3>
            <hr class="colorgraph">
            
            <input type="text" name="username" id="username" placeholder="Username" class="input form-control" autocomplete="off" required autofocus><br>
            <input type="email" name="email" id="email" placeholder="Email" class="input form-control" autocomplete="off" required><br>
            <input type="password" name="password" id="password" placeholder="Password" class="input form-control" autocomplete="off" required>
            <input type="password" name="password2" id="password2" placeholder="Re-enter password" class="input form-control" autocomplete="off" required><br>
            
            <!-- Show messages -->
            <?php if(!empty(@$_SESSION['errorMessage'])){
            $registration->messages(); }?><br>
                        
            <input type="submit"  name="registration" value="Sign Up" class="btn btn-lg btn-block submit" />  
        
        </form>
        
    </div>  <!-- End registrationForm-->
 
<!-- URL to login form -->
<div class="cnt"><a href="login.php">Already signed up? Log in here</a></div>

<!-- Remove this to remove the GitHub URL link -->
<div class="cnt gray"><a href="https://github.com/MartinoEst/secured-php-login">Check out this code at GitHub</a></div>

<!-- Back to main page -->  
<div class="cnt gray"><a href="index.php">Back to main page</a></div>  
  
</div>
<!-- End div -->

