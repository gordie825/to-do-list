<?php
//start the session
session_start();

//check if the user is logged in, if they are not then refirect them to the login page. 

require_once 'function/functions.php';
// isUserLoggedin();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
require_once "config/conn.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

//Processing form data when the form is submitted

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $sanpost_new_password = filter_var($_POST["new_password"],FILTER_SANITIZE_STRING);

    $sanpost_cofirm_password = filter_var($_POST["confirm_password"],FILTER_SANITIZE_STRING);

    //Validate new password

    $validate_password = preg_match_all('^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$^',$sanpost_new_password);

    if(!$validate_password){
        $new_password_err = "Choose a better password.";
    } else{
        //password entered and confirmed password do not match
        if($sanpost_new_password != $sanpost_confirm_password){

            $confirm_password_err = "Passwords do not match.";

        } else{
            $new_password = $sanpost_new_password;
        }
    }
    //Check input errors before updating the database

    if(empty($new_password_err) && empty($confirm_password_err)){

        //prepare an update statement
    
        $sql = "UPDATE users SET password = :password WHERE id = :id";

        //change the password stored in the database in the row associated with the user id logged in. 

        if($stmt = $pdo->prepare($sql)) {
            //Bind variables to the prepared statement as parameters. 
            
            //password bind
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            //id bind
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

            //Set parameters

            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();

                header("location: login.php");
                exit();

            } else{
            echo "Something went wrong. Please try again later.";
            }

        }
        //Close statement
        unset($stmt);

    }
    //close connection
    unset($pdo);
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!--Bootstrap CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!--Font awesome-->
        <script src="https://kit.fontawesome.com/0cd95c0d58.js" crossorigin="anonymous"></script>
        <!--Custom CSS-->
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>Reset</title>
    </head>
<body>
    <div class="container login-page-container ">

        <div class="container mt-4">
            <div class="row">
            <div class="col-12 login-title text-center">
                <h1>Reset Password</h1>
                <p>Please fill out this form to reset your password.</p>
            </div>
            </div>
        </div>

        <!--Login form-->
        <div class="container form-container my-4">
            <div class="row">
            <div class="col-sm-12">
            <form role="form" method="POST" id="regform">
            <!--USERNAME DIV-->
                <!-- <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name ="username"aria-describedby="emailHelp" placeholder="Enter username." required>
                    <!--GENERATE USERNAME ERROR-->
                    <!-- <small class="error"> Please enter &nbsp; <?php echo $username_err;?></small>
                </div> -->

            <!--PASSWORD DIV-->
                <div class="form-group">
                    <label for="newpassword"> New Password</label>
                    <input type="password" class="form-control" id="newpassword" name="new_password"placeholder=" Pease enter your new passsword." required>
                    <!--GENERATE PASSWORD ERROR-->
                    <small id="password_error" class="error"><?php echo $new_password_err;?></small>
                </div>
             <!--CONFIRM PASSWORD DIV-->   
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"placeholder="Confirm Password" required>
                    <!--GENERATE PASSWORD CONFIRM ERROR-->
                    <small id="confirm_error"class="error"><?php echo $confirm_password_err;?> </small>
                </div>

                <div class="form-group">
                <button type="submit" class="btn btn-primary sign-in-btn">Reset Password</button>
                <a class="btn btn-link" href="welcome.php">Cancel</a>
                </div>
                
            </form>
            </div>
            </div>
        </div>
        <!--Login form-->

    </div>
    <script src="./js/register.js"></script>
</body>
</html>