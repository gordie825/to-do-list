<?php
 function isUserLoggedin(){

    if(isset($_SESSION["loggedin"])&& $_SESSION["loggedin"] === true){

        header("location: welcome.php");
        exit;
    } 
    // else{
    //     header("location: login.php");
    //     exit;
    // }
 }


 //validate the password server side $sanpost_password $sanpost_confirm_pass
function validPassword($password,$confirm_password){

    $validate_password = preg_match_all('^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$^',$password);

    if(!$validate_password){
        return false;
    } else{
        if($password != $confirm_password){
            return false;
        }
        else{
            return $password = "sdsdsd";
        }
        
    }
}

;

// if(!$validate_password){
//     $password_err = "Choose a better password.";
//     // echo "choose a better password". $sanpost_password;
// }else{
//     if($sanpost_password != $sanpost_confirm_pass){
//         $confirm_password_err = "Passwords do not match.";
//     } //Password matches the correct pattern and matches input in confirm password field
//     else{
//         $password = $sanpost_password;

//     }
// }
?>