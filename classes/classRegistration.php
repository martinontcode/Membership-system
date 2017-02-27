<?php 

class Registration{
    
    /* __constructor()
     * Constructor will be called every time Registration class is called ($registration = new Registration())
     */
     public function __construct(){

        /* If registration data is posted call createUser function. */
        if (isset($_POST["registration"])) {
            $this->isPasswordValid();
        }
        
    } /* End __construct() */
    
    
    
    
    /* Function isPasswordValid(){
    * This function checks that both provided password are the same,
    * if not then user is prompt an error that the password do not match.
    * Else createUser() function is called.
    */
    private function isPasswordValid(){
        if(isset($_POST['registration'])){
            
            /* Require credentials for DB connection. */
            require ('config/dbconnect.php');

            $password = mysqli_real_escape_string($conn,trim($_POST['password']));
            $password2 = mysqli_real_escape_string($conn,trim($_POST['password2']));
            if($password===$password2){
                $this->createUser();
            } else {
                /* If registration fails return user to registration.php and promt user failure error. */
                header('Location: registration.php');
                $_SESSION['errorMessage'] = 5;
                exit();
            }
        }
        
    } /* isPasswordValid() */
    
    /* Function createUser(){
    * Function that includes everything for new user creation.
    * Data is taken from registration form, converted to prevent SQL injection and
    * checked that values are filled, if all is correct data is entered to user database.
    */
    private function createUser(){
    
        /* Require credentials for DB connection. */
        require ('config/dbconnect.php');

            /* User input variables converted to string to prevent SQL injections. */
            $username = mysqli_real_escape_string($conn,trim($_POST['username']));  
            $password = mysqli_real_escape_string($conn,trim($_POST['password']));
            $password2 = mysqli_real_escape_string($conn,trim($_POST['password2']));
            $email = mysqli_real_escape_string($conn,($_POST['email']));
            $hash = md5( rand(0,1000) );
            
           
            
            if($password===$password2){
                /* This is where we hash the password. */
                $securing = password_hash($password, PASSWORD_DEFAULT);

                /* Check that all fields are filled with values. */
                if(!empty($username) && !empty($password) && !empty($email)){

                    /* Check if username or email is already taken */
                    $checkavailable = "SELECT * FROM `users` WHERE username = '$username' OR email = '$email' "; // Query to cross check Company name with database.
                    $result = mysqli_query($conn, $checkavailable);
                    
                    if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)){
    			header('Location: registration.php');
                        $_SESSION['errorMessage'] = 6;
                        exit();
		    }

                    /* If username or email is taken */
                    if ($result->num_rows != 0) {
                        /* Promt user error about username or email already taken */
                        header('Location: registration.php');
                        $_SESSION['errorMessage'] = 3;
                        exit();
                    } else {
                        /* Insert data into database. */
                        $sql = "INSERT INTO `users` (`id`, `username`, `email`, `password`, `hash`) VALUES ('', '$username', "
                            . "'$email', '$securing', '$hash');";
                        $result = mysqli_query($conn, $sql);
			$to = $email; // Send email to our user
    			$subject = 'Register | Verification'; // Give the email a subject 
    			$message = '
 
			Thanks for signing up '.$username.'!
			Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
 
			Please click this link to activate your account:
			https://www.website.com/verify.php?email='.$email.'&hash='.$hash.'
 
			'; // Our message above including the link
                     
			$headers = 'From:noreply@website.com' . "\r\n"; // Set from headers
			mail($to, $subject, $message, $headers); // Send our email
                        /* If registration is successful return user to registration.php and promt user success pop-up. */
                        header('Location: registration.php');
                        $_SESSION['errorMessage'] = 4;
                        exit();
                    } /* /EndIF */  

                } else {
                    /* If registration fails return user to registration.php and promt user failure error. */
                    header('Location: registration.php');
                    $_SESSION['errorMessage'] = 2;
                    exit();
                }
            }
                
        
    }   /* End createUser() */

    
    /* Function messages(){
    *  Messages used in registration form.
    */
    public function messages(){
        switch (@$_SESSION['errorMessage']){
            case "2":
                echo "Please fill all fields!";
                break;
            case "3":
                echo "Username or email is taken!";
                break;
            case "4":
                echo "User has been created!";
                break;
            case "5":
                echo "Passwords do not match!";
                break;
        }
    }   /* End messages() */
    
   
    
    
} /* End class Registration */
