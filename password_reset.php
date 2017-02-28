<?php if(!isset($_SESSION)) { session_start();} 

/* Require UserClass.php to call registration function */
require("classes/UserClass.php");

/* Call for class constructor */
$passwordReset = new UserClass();
$passwordReset->newPassword();
?>
<!-- If there is an error it will be shown. --> 
<?php if(!empty($_SESSION['message'])): ?>
    <div class="alert alert-danger alert-container" id="alert">
        <strong><center><?php echo htmlentities($_SESSION['message']) ?></center></strong>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

