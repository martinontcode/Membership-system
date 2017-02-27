<?php 

class Login{
    
    /* __constructor()
     * Constructor will be called every time Login class is called ($login = new Login())
     */
     public function __construct(){
        
        /* Check if user is logged in. */
        $this->isLoggedIn();
         
        /* If login data is posted call validation function. */
        if (isset($_POST["login"])) {
            $this->validateLogIn();
        }     
        /* If forgot password form data is posted call forgotPassword() function. */
        if (isset($_POST["forgotPassword"])) {
            $this->forgotPassword();
        }    
        if (isset($_POST["updatePassword"])) {
            $this->updatePassword();
        } 
                       
    } /* End __construct() */
    
 
    /* function validateLogIn()
     * Function that validates user login data, cross-checks with database.
     */
    public function validateLogIn(){
    
    // Require credentials for DB connection. 
    require ('config/dbconnect.php');
        
    // Check that data has been submited through login form.
    if(isset($_POST['login'])){
        
        // User input.
        $user = trim($_POST['username']);
        $userpsw = trim($_POST['password']);

        // Check that both fields are filled with values.
        if(!empty($user) && !empty($userpsw)){

            /* Query the username from DB, if response is greater than 0 it means that users exists & 
             * we continue to compare the password hash provided by the user side with the DB data. */
            
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            if ($result->num_rows === 1) {
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                if (password_verify($userpsw, $row['password'])) {      // Example of password hash : $4x$80$Vcl0Wxr5DNeIg.Y52YiVOOePENcjQPJ88mrEacKP15S9kIhx.u6gy
                    $_SESSION['user_id'] = $row['username'];    // Username is set as Session user_id for this user.            
                } else {
                    $_SESSION['message'] = 'Invalid username or password.';
                } 
            } else {
                $_SESSION['message'] = 'Invalid username or password.';
            }
        }
    } else {
        header('location: login.php'); // Prompt user to fill all fields.
    }

    } /* End validateLogIn() */
  
  
    /* function isLoggedIn()
     * Check if user is already logged in, if not then prompt login form.
     */
    public function isLoggedIn(){
        
    /* Require credentials for DB connection. */
    require ('config/dbconnect.php');

        if(!empty(@$_SESSION['user_id'])){   
            return true;        
        } else {    
            return false;
        }

    } /* End isLoggedIn() */
    
    
    /* function logOut()
     * Logs user out, destroys all session data.
     */
    public function logOut(){
        
        session_destroy();  // Destroy all session data.
        header('Location: index.php');
        
    } /* End logOut() */   
    
    
    /* function forgotPassword()
     * If the email exists $forgot_password_key is created to database, after this user will be sent an reset key via e-mail.
     */
    private function forgotPassword(){
        $email = trim($_POST['email']);
        
        // Require credentials for DB connection. 
        require ('config/dbconnect.php');
        
        // Check if username or email is already taken.
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        // Always give this message to prevent data colleting even if the e-mail doesn't exist(The password reset e-mail is only sent if the e-mail exists in database).
        $_SESSION['SuccessMessage'] = 'E-mail has been sent.';
        
        // If e-mail exists a key for password reset is created into database, after this an e-mail will be sent to user with link and the token key.
        if ($result->num_rows != 0) {
            $forgot_password_key = password_hash($email, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET fpassword_key = ? WHERE email = ?");
            $stmt->bind_param("ss", $forgot_password_key, $email);
            $stmt->execute();
            $stmt->close();
            
            $message = "Your reset key is: ".$forgot_password_key."";
            $to= $email;
            $subject="Reset password";
            $from = 'test@test.com'; // Insert the e-mail where you want to send the emails from.
            $body='<a href="YOURWEBSITEURL/password_reset.php?email='.$email.'&key='.$forgot_password_key.'">password_reset.php?email='.$email.'&key='.$forgot_password_key.'</a>'; // Replace YOURWEBSITEURL with your own URL for the link to work.
            $headers = "From: " .$from. "\r\n";
            $headers .= "Reply-To: ". $from . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to,$subject,$body,$headers);
        }
 
    } /* End forgotPassword() */
    
    
    /* function newPassword()
     * URL opened from e-mail, get email & token key from URL.
     * If the e-mail and token exist in database prompt new password form.
     * Otherwise prompt an error.
     */
    public function newPassword(){
        $email = htmlentities($_GET['email']);
        $forgot_password_key = htmlentities($_GET['key']);
        
        // Require credentials for DB connection. 
        require ('config/dbconnect.php');
        
        $stmt = $conn->prepare("SELECT email,fpassword_key  FROM users WHERE email = ? AND fpassword_key = ?");
        $stmt->bind_param("ss", $email, $forgot_password_key);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        if ($result->num_rows != 0) {
            include("views/passwordResetForm.php");
        } else {
            $_SESSION['message'] = 'Please contact support at support@membership.com';
        }
        
    } /* End newPassword() */
    
    
    /* function updatePassword()
     * Get information from Password Reset Form, if the email & token key are correct, update the passwordin database.
     */
    private function updatePassword(){
        $password3 = trim($_POST['password3']);
        $password2 = trim($_POST['password2']);
        $email = $_POST['email'];
        $forgot_password_key = $_POST['key'];
        
        // Require credentials for DB connection. 
        require ('config/dbconnect.php');
        
        if($password3===$password2){

            if(!empty($password3) && !empty($email)){
                $securing = password_hash($password2, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ?, fpassword_key = ?  WHERE email = ? AND fpassword_key = ?");
                $clean_password_key = "";
                $stmt->bind_param("ssss", $securing, $clean_password_key, $email, $forgot_password_key);
                $stmt->execute();
                $stmt->close();
            } else {
                $_SESSION['message'] = 'Please fill all required fields.';
            }
            
        } else {
            $_SESSION['message'] = 'Passwords do not match!';
        }
        
    } /* End updatePassword() */
    
    
} /* End class Login */


