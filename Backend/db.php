<?php
/* DATABASE CONFIGURATION
   This file establishes the connection to the MySQL database.
   It is required by all backend handlers and database function files.
*/

$server = "localhost";
$user = "root";
$password = "";
$database = "homefix_db"; // Configured for the HomeFix database schema

// Create the connection using the procedural mysqli method
$connection = mysqli_connect($server, $user, $password, $database);

// Check if the connection was successful
if (!$connection) {
    // If the connection fails, stop the script and display the error
    die("Connection failed: " . mysqli_connect_error());
}
?>