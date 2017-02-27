<?php if(!isset($_SESSION)) { session_start();} 

/* Require UserClass.php to call registration function */
require("classes/classLogin.php");

/* Call for class constructor */
$pswReset = new Login();
?>

<?php include_once 'header.php'; ?>

<div class="container">

    <!-- Login form -->
    <div class="loginForm">
        <form  action="forgot.php" name="forgotpsw" class="form-forgot" method="post">
            <h3 class="cnt">Forgot your password?</h3>
            <hr class="colorgraph">
            
            <p class="">Enter your email. We'll email instructions on how to reset your password.</p>
            
            <label for="email">E-mail<span class="red">*</span>:</label>
            <input type="email" name="email" id="email" placeholder="E-mail" class="input form-control" autocomplete="off" required autofocus><br>
            
            <!-- If there is an error it will be shown. --> 
            <?php if(!empty($_SESSION['message'])): ?>
                <div class="alert alert-danger alert-container" id="alert">
                    <strong><center><?php echo htmlentities($_SESSION['message']) ?></center></strong>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <input type="submit"  name="forgot" value="Send e-mail" class="btn btn-lg btn-block submit" /> 
            
        </form>



    </div>  <!-- End loginForm-->

</div>  