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
                        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
                        $stmt->bind_param("sss", $username, $email, $securing);
                        $stmt->execute();
                        $stmt->close();

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

} /* End class Registration */


