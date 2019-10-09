<?php
session_start();
// unset($_SESSION['todo']);
// creating variables to use in index.php
$todo = $_POST['todo_input'];
$date = $_POST['todo_date'];
$submit = $_POST['submit'];
$delete_todo = $_GET['delete'];

// store all the variables in an array so we can use foreach loop
$_SESSION['todo'][] = [
    "todo_item" => $todo,
    "todo_dates" => $date,
    "delete" => $delete_todo
];

// importing the variables to index.php
if (isset($todo, $date)) {
    header("location:welcome.php");
}