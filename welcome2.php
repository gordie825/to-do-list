<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
//collect user details 
//userid
//username
$user_id = $_SESSION["id"];
 $username = $_SESSION["username"];

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--JQUERY-->
    <script src="js/jquery-3.4.1.js"></script>
    
    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <script src="https://kit.fontawesome.com/0cd95c0d58.js" crossorigin="anonymous"></script>
    <!--Custom CSS-->
    <link rel="stylesheet" type="text/css" href="css/stylewelcome.css">

    <title>Welcome <?php echo $username;?></title>
</head>
<body>
    <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">NAVs-ToDo</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i> <?php echo $username;?>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="resetpass.php"><i class="fas fa-unlock"></i>&nbsp;Reset Password</a>
                    <a class="dropdown-item" href="logout.php"><i class="fab fa-angellist"></i>&nbsp;Log Out</a>
                    </div>
                </li>
                </ul>
            </div>
        </nav>
    </header>


    <div class="text-center text-light my-4">
        <h1 class="mb-4">Welcome <?php echo $username;?>, here is your to do list...</h1>
    </div>

    <div class="container">
        <div class="text-center text-light my-4">
            <h3 class="task-center form-header"> Search Tasks</h3>
            <form class="search" action="">
                <input class="form-control m-auto" type="text" name="search" placeholder="Search Todos">
            </form>
        </div>
       
        <div class="form-header my-3 d-flex justify-content-between">
            <span>Tasks</span>
            <!-- <span>Due Date</span> -->
            <span>Delete/Edit Tasks</span>
        </div>
       

        <ul class="list-group todos mx-auto text-light">
        <!--PHP CLASSS START-->
            <?php
            require 'config/conn.php';
            $user = new Todo($pdo,$user_id);

            $user->display();
            ?>
        <!--PHP CLASSS END-->
        </ul>

        <div class="text-center mt-5">
            <h3 class="form-header">Add Task</h3>
        </div>

        <!--Add Task Form-->
        <form class=" add form-inline mt-5 d-flex justify-content-between" method="POST">

        <input type="text" class="form-control mb-2 mr-sm-2" id="taskname" placeholder="Add Task" name="add" required>

        <!-- <div class="input-group mb-2 mr-sm-2">
            <div class="input-group-prepend">
            <div class="input-group-text">Date</div>
            </div>
            <input type="date" class="form-control" id="duedate" name ="date" placeholder="Enter task due date" required>
        </div> -->

        <button type="submit" class="btn btn-dark mb-2" name="addTask" >Add Task</button>
        </form>

    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="./js/ajx.js"></script>
   <!-- //<script src="./js/welcome.js"></script> -->
</body>
</html>


<?php
if(isset($_POST["addTask"])){
    $user_id;
    $task = $_POST['add'];
    // $date = $_POST['date'];
    $user->insert($task);
    

}
?>

<?php 
 class Todo {
    
    public $pdo,$user_id,$task,$date;
    function __construct($param_PDO,$param_user_id)

    {
        $this->pdo = $param_PDO;
        $this->user_id = $param_user_id;
        // $this->task = $param_task;
        // $this->date = $param_date;
    }

    function insert($param_task)

    {
        $this->task = $param_task;
        // $this->date = $param_date;
       
        //prepare insert statement
        $sql = "INSERT INTO  items ( user_id,list_item) VALUES (:user_id,:list_item )"; 

        if($stmt = $this->pdo->prepare($sql))
        {

        //bind parameters to the prepared statements
        $stmt->bindParam(":list_item",$param_list_item,PDO::PARAM_STR);
        // $stmt->bindParam(":due_date",$param_due_date,PDO::PARAM_STR);
        $stmt->bindParam(":user_id",$param_userID,PDO::PARAM_INT);

        //set the parameters
        $param_userID = $this->user_id;
        $param_list_item = $this->task;
        // $param_due_date = $this->date;
        

        //execute the prepared statement
        if($stmt->execute()){

        }
        else{
            echo "didnt execute";
        }
        unset($stmt);
        unset($pdo);
        }
        else{
            echo  "Please press add task";
        }
    }

    function display(){

        include_once 'config/config.php';

        $sql = "SELECT * FROM items WHERE user_id=:user_id ORDER BY id ASC";

        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":user_id",$param_userID,PDO::PARAM_INT);

            $param_userID = $this->user_id;
            
            if($stmt->execute()){
                //fetch associative array
            while($data = $stmt->fetch( PDO::FETCH_ASSOC )){

                $task_id= $data["item_id"];
                $task = $data['list_item'];
                // $date = $data['due_date'];
                // $status = $data["class"];

                echo<<<END

                <li id="listitem-$task_id" class="list-group-item d-flex justify-content-between align-items-center $status">
               <span>$task</span>&nbsp;<span>$date</span></i><span><input type="submit" id="delete_button-$task_id" class="btn btn-dark fas delete" value="&#xf2ed" onclick="delete_row('$task_id')">  
               <input type="submit" class="btn btn-dark fas check" value="&#xf00c" onclick="complete_task('$task_id')"></span>  </li>
END;
  
               }
            }
            
        }
    }
}
?>