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
                       
    } /* End __construct() */
    
 
    /* function validateLogIn()
     * Function that validates user login data, cross-checks with database.
     */
    public function validateLogIn(){
    
    /* Require credentials for DB connection. */
    require ('config/dbconnect.php');
        

    if(isset($_POST['login'])){


        $user = mysqli_real_escape_string($conn,trim($_POST['username']));
        $userpsw = mysqli_real_escape_string($conn,trim($_POST['password']));
        
        


        /* Check that both fields are filled with values */
        if(!empty($user) && !empty($userpsw)){

            
            $sql = "SELECT * FROM `users` WHERE username = '$user'";
        
            $result = mysqli_query($conn, $sql);
            if ($result->num_rows === 1) {
                
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                if (password_verify($userpsw, $row['password']) && $row['active'] == 1)  {
                    $_SESSION['user_id'] = $user;                    
                } elseif ($row['active'] == 0) {
                    $_SESSION['errorMessage'] = 7;
                } else {
                    $_SESSION['errorMessage'] = 1;
                } 
                
            }
            
        }
        } else {
            echo 'Please fill all fields.'; // Prompt user to fill all fields.
        }   /* /EndIF */

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
    
    
} /* End class Login */

