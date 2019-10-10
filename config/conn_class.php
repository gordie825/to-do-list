<?php 
require 'base_class.php';

//create a conncetion
try{
    $pdo = new PDO ("mysql:host=".DB_HOST_CLASS .";dbname=" . DB_NAME_CLASS,DB_USERNAME_CLASS, DB_PASS_CLASS);
    //Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
}
catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());

}
?>