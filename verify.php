<?php require_once 'header.php'; ?>

<?php 
    /* Require login.php to call login function */
    require("classes/UserClass.php");

    /* Call for login function */
    $verify = new UserClass();
    
    $verify->Verify();
?> 
<div class="container container-content">
    <?php if($verify->Verify() == TRUE): ?>
    <h3 class="text-center">Account has been activated.</h3>
    <p class="text-center">To login click on <a href="login.php" class="btn btn-primary btn-sm">Login</a></p>
    <?php elseif($verify->Verify() == FALSE): ?>
    <h3 class="text-center">There has been an error while activating your account.</h3>
    <p class="text-center">Please contact support at <a href="mailto:support@membership.com?Subject=Konto%aktiveerimise%viga" class="link-blue">support@membership.com</a>.</p>
    
    <?php endif; ?>  
</div>
