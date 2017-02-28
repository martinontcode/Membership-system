<?php include_once 'header.php'; ?>

<div class="container">

    <!-- Forgot password form -->
    <div class="forgotpassword-Form">
        <form  action="forgot.php" name="forgotpassword-Form" class="form-forgot" method="post">
            <h3 class="cnt">Insert new password.</h3>
            <hr class="colorgraph">
            <label for="password3">New password<span class="red">*</span>:</label>
            <input type="password" name="password3" id="password3" placeholder="Re-enter password" class="input form-control" autocomplete="off" required>
            <label for="password2">Re-enter password<span class="red">*</span>:</label>
            <input type="password" name="password2" id="password2" placeholder="Re-enter password" class="input form-control" autocomplete="off" required><br>
            <input type="text" name="email" value="<?php  echo htmlentities($_GET['email']); ?>" hidden >
            <input type="text" name="key" value="<?php echo htmlentities($_GET['key']); ?>" hidden >
                        
            <input type="submit"  name="updatePassword" value="Update password" class="btn btn-lg btn-block submit" />  
        
        </form>
        
    </div>  <!-- End Forgot password form-->
</div> 