<?php 

class Registration{
    
    /* __constructor()
     * Constructor will be called every time Registration class is called ($registration = new Registration())
     */
     public function __construct(){

        /* If registration data is posted call createUser function. */
        if (isset($_POST["registration"])) {
            $this->createUser();
        }
        
    } /* End __construct() */

    
    /* Function createUser(){
    * Function that includes everything for new user creation.
    * Data is taken from registration form, converted to prevent SQL injection and
    * checked that values are filled, if all is correct data is entered to user database.
    */
    private function createUser(){
    
        // Require credentials for DB connection.
        require ('config/dbconnect.php');

            // User input variables converted to string to prevent SQL injections.
            $username = trim($_POST['username']);  
            $password = trim($_POST['password']);
            $password2 = trim($_POST['password2']);
            $email = $_POST['email'];
            
            if($password===$password2){
                // Create hashed user password.
                $securing = password_hash($password, PASSWORD_DEFAULT);

                // Check that all fields are filled with values.
                if(!empty($username) && !empty($password) && !empty($email)){

                    // Check if username or email is already taken.
                    $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
                    $stmt->bind_param("ss", $username, $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    
                    // Check if email is in the correct format.
                    if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)){
                        header('Location: registration.php');
                        $_SESSION['message'] = 'Please insert correct e-mail.';
                        exit();
                    }
                    
                    // If username or email is taken.
                    if ($result->num_rows != 0) {
                        // Promt user error about username or email already taken.
                        header('Location: registration.php');
                        $_SESSION['message'] = 'Username or e-mail is taken!';
                        exit();
                    } else {
                        // Insert data into database
                        $code = substr(md5(mt_rand()),0,15);
                        $stmt = $conn->prepare("INSERT INTO users (username, email, password, activation_code) VALUES (?,?,?,?)");
                        $stmt->bind_param("ssss", $username, $email, $securing, $code);
                        $stmt->execute();
                        $stmt->close();
                        
                        // Send user activation e-mail
                        
                        $message = "Your activation code is: ".$code.".";
                        $to= $email;
                        $subject="Your activation code for Membership.";
                        $from = 'test@membership.com';  // This should be changed to an email that you would like to send activation e-mail from.
                        $body='Your activation code is: '.$code.'<br> To activate your account please click on the following link'
                                . ' <a href="http://localhost/Membership/verify.php?id='.$email.'&code='.$code.'">verify.php?id='.$email.'&code='.$code.'</a>.';
                        $headers = "From: " .$from. "\r\n";
                        $headers .= "Reply-To: ". $from . "\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        mail($to,$subject,$body,$headers);

                        // If registration is successful return user to registration.php and promt user success pop-up.
                        header('Location: registration.php');
                        $_SESSION['SuccessMessage'] = 'User has been created!';
                        exit();
                    } 

                } else {
                    // If registration fails return user to registration.php and promt user failure error.
                    header('Location: registration.php');
                    $_SESSION['message'] = 'Please fill all fields!';
                    exit();
                }
            } else {
                header('Location: registration.php');
                $_SESSION['message'] = 'Passwords do not match!';
                exit();
            }
        
    }   /* End createUser() */
    
    
    /* Function Verify(){
    *  Check if user has verified his/her email.
    */
    public function Verify(){
        if(isset($_GET['id']) && isset($_GET['code']))
        {
            $user_email=$_GET['id'];
            $activation_code=$_GET['code'];
            
            /* Require credentials for DB connection. */
            require ('config/dbconnect.php');

            $stmt = $conn->prepare("SELECT email FROM users WHERE email = ? and activation_code = ?");
            $stmt->bind_param("ss", $user_email, $activation_code);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close(); 
            if($result->num_rows == 1)
            {
                $stmt = $conn->prepare("UPDATE users SET is_activated = ? WHERE email = ? and activation_code = ?");
                $verified = 1;
                $stmt->bind_param("iss", $verified, $user_email, $activation_code);
                $stmt->execute();
                $stmt->close();
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }   /* End Verify() */

} /* End class Registration */


