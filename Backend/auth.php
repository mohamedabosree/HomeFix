<?php
/* BACKEND AUTHENTICATION MODULE
 * Handles user sessions, login, registration, and role verification.
 * Updated to integrate strict academic session flags and timestamps.
 */

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function loginUser($email, $password) {
    global $connection;
    $safe_email = mysqli_real_escape_string($connection, $email);
    $safe_pass = mysqli_real_escape_string($connection, $password);
    
    // Instructor logic: Query checks both username and password directly
    $query = "SELECT id, name, email, role, picture FROM users WHERE email = '$safe_email' AND password = '$safe_pass' LIMIT 1";
    
    // Send SQL query to DB server
    $result = mysqli_query($connection, $query);
    
    // Fetch data from DB as array
    if ($row = mysqli_fetch_array($result)) {
        // Explicit session flags and timestamps
        $_SESSION["Login"] = "YES";
        $_SESSION["datetime"] = date("h:i:s - D, d/M/Y");
        
        // Retain specific data for platform dashboard
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['picture'] = $row['picture'] ?? 'default.png';
        return true;
    } else {
        // Explicit denial flag
        $_SESSION["Login"] = "NO";
        return false;
    }
}

function registerUser($name, $email, $password) {
    global $connection;
    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_email = mysqli_real_escape_string($connection, $email);
    $safe_pass = mysqli_real_escape_string($connection, $password);
    
    $check = mysqli_query($connection, "SELECT id FROM users WHERE email = '$safe_email'");
    if (mysqli_num_rows($check) > 0) {
        return false; 
    }

    $query = "INSERT INTO users (name, email, password, role) 
              VALUES ('$safe_name', '$safe_email', '$safe_pass', 'user')";
              
    if (mysqli_query($connection, $query)) {
        return mysqli_insert_id($connection);
    }
    return false;
}

function logoutUser() {
    $_SESSION = array();
    session_destroy();
}

function isLoggedIn() {
    // Check against the instructor's specific login flag
    return isset($_SESSION["Login"]) && $_SESSION["Login"] === "YES";
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>