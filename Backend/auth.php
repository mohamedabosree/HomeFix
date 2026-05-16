<?php
/* BACKEND AUTHENTICATION MODULE
 * Handles user sessions, login, registration, and role verification.
 * Updated for the 10-Table ERD Architecture.
 */

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function loginUser($email, $password) {
    global $connection;
    $safe_email = mysqli_real_escape_string($connection, $email);
    
    $query = "SELECT id, name, email, password, role FROM users WHERE email = '$safe_email' LIMIT 1";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) === 0) {
        return false;
    }
    
    $user = mysqli_fetch_assoc($result);
    
    if ($password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

function registerUser($name, $email, $password) {
    global $connection;
    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_email = mysqli_real_escape_string($connection, $email);
    $safe_pass = mysqli_real_escape_string($connection, $password);
    
    // Halt execution if the email is already established
    $check = mysqli_query($connection, "SELECT id FROM users WHERE email = '$safe_email'");
    if (mysqli_num_rows($check) > 0) {
        return false; 
    }

    // Execute user commitment without location_id
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
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>