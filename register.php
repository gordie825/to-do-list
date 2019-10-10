<?php
require 'config/conn.php';
$username= $password=   $confirm_password = "";
$username_err=$password_err=$confirm_password_err="";

//Send the form data to be processed

if($_SERVER["REQUEST_METHOD"] == "POST"){

    //sanitize post_variables
    $sanpost_username =ucfirst( filter_var($_POST["username"],FILTER_SANITIZE_STRING));

    $sanpost_password = filter_var($_POST["password"],FILTER_SANITIZE_STRING);

    $sanpost_confirm_pass = filter_var($_POST["confirm_pass"],FILTER_SANITIZE_STRING);

    //validate username
    if(empty(trim($sanpost_username))){
        $username_err = "Please enter a username.";
    } else{
        //prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";
        if($stmt = $pdo->prepare($sql)){

        $stmt->bindParam(":username",$param_username,PDO::PARAM_STR);
        // Set parameters
        $param_username = trim($sanpost_username);

        //execute prepared statement and check if execution was successful
        if($stmt->execute()){
            
            if($stmt->rowCount()==1){ // if this evaluates to true then there is another user with the same username in the database.
                $username_err = "This username is taken. Please change your username.";
            } else{
                //Username is available to use.
                $username = trim($sanpost_username);
            }
        } else{
            //This else clause will ran because the query to the database failed to excute. Could be a connection issse.

            //Redirect to a page to tell user to try connect later?

            echo "Please try again later. Something went wrong.";
        }
    }
    //Close statement
    unset($stmt);
}

//validate the password server side $sanpost_password $sanpost_confirm_pass

$validate_password = preg_match_all('^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$^',$sanpost_password);

if(!$validate_password){
    $password_err = "Choose a better password.";
    // echo "choose a better password". $sanpost_password;
}else{
    if($sanpost_password != $sanpost_confirm_pass){
        $confirm_password_err = "Passwords do not match.";
    } //Password matches the correct pattern and matches input in confirm password field
    else{
        $password = $sanpost_password;

    }
}

//Check for errors before insering into database 
if(empty($username_err)&& empty($password_err)&& empty($confirm_password_err)){
    //prepare an insert statement

    $sql = "INSERT INTO users (username, password) VALUES(:username,:password)";

    if ($stmt = $pdo->prepare($sql)){
        //Bind variables to the prepared statement as parameters

        $stmt->bindParam(":username",$param_username,PDO::PARAM_STR);
        $stmt->bindParam(":password",$param_password,PDO::PARAM_STR);

        //Set the parameters
        $param_username = $username;
        $param_password = password_hash($password,PASSWORD_DEFAULT);

        //Execute the prepared statement
        if($stmt->execute()){
            //redirect to welcome page
            header("location: welcome.php");
        }else{
            echo"something went wrong please try again later";
        }
    }
    //close statement
    unset($stmt);
    echo "ngiyafuna";
}
//close connectionzzzzz
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
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <title>Register</title>
    </head>
<body>
    <div class="container login-page-container ">

<!-- form start -->
<form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <p class="login-text">
    <span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x"></i>
      <i class="fa fa-lock fa-stack-1x"></i>
    </span>
  </p>
    
<!--  username div start    -->
  <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
  <input type="text" name="username" class="login-username" autofocus="true" required="true" placeholder="Username" value="<?php echo $username; ?>" />
  <span class="help-block"><?php echo $username_err; ?></span>
</div>
<!-- username div end  -->
  
<!--    password div start  -->
<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
  <input type="password" name="password" class="login-password" required="true" placeholder="Password" value="<?php echo $password; ?>"/>
  <span class="help-block"><?php echo $password_err; ?></span>
</div> 
<!-- password div end -->
    
<!--  confirm password div start    -->
<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
  <input type="password" name="confirm_pass" class="login-password" required="true" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>"/>
  <span class="help-block"><?php echo $confirm_password_err; ?></span>
</div> 
<!--  confirm password div end    -->
    
  <input type="submit" name="submit" value="REGISTER" class="login-submit" />


<a href="login.php" class="login-forgot-pass">Have An Account? Login Here</a>
<div class="underlay-photo"></div>
<div class="underlay-black"></div> 
</form>
<!-- form end -->
        
    </div>
    <script src="./js/register.js"></script>
</body>
</html>
