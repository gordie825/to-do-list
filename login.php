<?php
session_start();

//check if a user is already logged in, redirect to welcome page if logged in

require_once 'function/functions.php';
isUserLoggedin();
// if(isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]=== true){

//     header("location: welcome.php");
//     exit;
// }
require_once "config/conn.php";
//define variables and initialize with empty values

$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //sanitize post_variables
    $sanpost_username = filter_var($_POST["username"],FILTER_SANITIZE_STRING);

    $sanpost_password = filter_var($_POST["password"],FILTER_SANITIZE_STRING);


    //Validate Username
    if(empty($sanpost_username)){
        $username_err = "Please enter username.";
    } else{
        $username = $sanpost_username;
    }
    //Validate Password
    if(empty($sanpost_password)){
        $password_err = "Please enter your password.";
    } else{
        $password = $sanpost_password;
    }

    //validate the user credentials

    if(empty($username_err) && empty($password_err)){

       
        // the error variables should be empty for a succesfful log in.

        //Prepare a selct statement
        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        
        if($stmt=$pdo->prepare($sql)){
            
            //Bind variables to the prepared statement as parameters

            $stmt->bindParam(":username",$param_username,PDO::PARAM_STR);

            //set parameters
            $param_username = $username;

            //Attempt to execute the prepared statement
            
            if($stmt->execute()){
                //the prepared statement was successfully executed
                //if the resource was succesfully created, and the number of rows is equla to 1.  now verify the password
                var_dump($username);

                if($stmt->rowCount() == 1){

                    if($data = $stmt->fetch()){

                        $user_id = $data["id"];

                        $username = $data["username"];

                        $hashed_password = $data["password"];

                        if(password_verify($password,$hashed_password)){
                          //Correct password if true
                          //start a new session
                          session_start();
                          //store data in session variables
                          
                          $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $user_id;
                          $_SESSION["username"] =$username;
                          //redirect user to welcome page
                          header("location: welcome.php");
                        } else{
                           //display an error message if username doesnt exist
                           $password_err="The password enetered was not valid." ;
                        }
                    } 

                }
                else{
                    //Username was not found
                    $username_err="No account found with this username";
                }
                
            }//error in fetch the data associated wuth stmt
            else{
                //redirect tp error page
                echo "oops something whent wrong in fething data please try again later";
            }
        }
        //close statement
        unset($stmt);
        
        
    }
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
    <title>To Do Login</title>
</head>
<body class="login-body">
    
    <div class="container mt-4">
        <div class="row">
        <div class="col-12 login-title text-center">
            <h1>DMF Task Manager</h1>
            
        </div>
        </div>
    </div>
    <div class="container login-page-container ">

        <!--Login form-->
        <div class="container form-container">
            <div class="row">
            <div class="col-sm-12">
                <form role="form" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" aria-describedby="emailHelp" placeholder="Enter username." name="username" required>
                    <small id="emailHelp" class="form-text text-muted"><?php echo $username_err;?></small>
                </div>

                <div class="form-group">
                    <label for="userpassword">Password</label>
                    <input type="password" class="form-control" id="userpassword" placeholder="Password" name="password" required>
                    <small id="emailHelp" class="form-text text-muted"><?php echo $password_err;?></small>
                </div>

                <button type="submit" class="btn btn-primary sign-in-btn">Sign in</button>

                <a href="resetpass.php" class="btn btn-light">Reset
                </a>


                <div class="flex-col-c p-t-170 p-b-40">
						<span class="txt1 p-b-9">
							Don’t have an account?
						</span>

						<a href="register.php" class="txt3">
							Sign up now
						</a>
                </div>
                </form>
            </div>
            </div>
        </div>
        <!--Login form-->

    </div>
    
</body>
</html>