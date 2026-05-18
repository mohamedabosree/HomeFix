<?php


$server = "localhost";
$user = "root";
$password = "";
$database = "homefix_db"; 


$connection = mysqli_connect($server, $user, $password, $database);


if (!$connection) {
   
    die("Connection failed: " . mysqli_connect_error());
}
?>
