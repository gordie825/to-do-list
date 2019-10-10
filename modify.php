<?php

require 'config/conn.php';

//delete task from database
if(isset($_POST["delete_task"]))
{
    $task_id = $_POST["task_id"];
    
    //prepare delete statement
    $sql = "DELETE  FROM items WHERE list_id = :list_id";
    if($stmt = $pdo->prepare($sql))
    {
        //bind parameters
        $stmt->bindParam(":list_id",$param_list_id,PDO::PARAM_STR);
        $param_list_id = $task_id;

        if($stmt->execute())
        {
            echo"SUCCESS";
        }
        else
        {
            echo"error";
        }
    }
    unset($stmt);
    unset($pdo);
}
//update taks status on database
if(isset($_POST["task_status"]))

{   $task_id = $_POST["task_id"];

    //query the database for item status
    $slct = "SELECT * FROM items WHERE list_id =:list_id";
    //prepare statement
    if($stmt1 =$pdo->prepare($slct))
    {
        //bind parameters
        $stmt1->bindParam(":list_id",$param_list_id,PDO::PARAM_STR);
        $param_list_id = $task_id;
        if($stmt1->execute())
        {
            //fetch an associative array
            $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            $task_status = $row["class"];
        }
    }
    unset($stmt1);
    unset($pdo);
    

    if($task_status == "complete")
    {
    //prepare update statement
    $sql = "UPDATE items SET class ='no' WHERE list_id = :list_id";
    echo $sql;
        if($stmt = $pdo->prepare($sql))
        {
        //bind parameters
        $stmt->bindParam(":list_id",$param_list_id,PDO::PARAM_STR);
        $param_list_id = $task_id;
        if($stmt->execute())
        {
            echo"SUCCESS";
        }
        else
        {
            echo"error";
        }
        }
    }
    elseif($task_status == "no")
    {
        require 'config/conn.php';
        //prepare update statement
    $sql = "UPDATE items SET class='complete' WHERE list_id=:list_id";

    if($stmt = $pdo->prepare($sql))
    {
        //bind parameters
        $stmt->bindParam(":list_id",$param_list_id,PDO::PARAM_STR);
        $param_list_id = $task_id;
        if($stmt->execute())
        {
            echo"SUCCESS";
        }
        else
        {
            echo"error";
        }
    }
    }
    else{
        var_dump($row); //"hello: ". $row["class"];
    }

    
}