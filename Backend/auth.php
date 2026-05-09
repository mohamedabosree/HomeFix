<?php
/* * BACKEND AUTHENTICATION MODULE
 * Handles user sessions, login, registration, and role verification.
 */

// Require the database connection
require_once 'db.php';

// Start a secure session if one does not already exist
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Validates credentials and logs a user in.
 */
function loginUser($email, $password) {
    global $connection;

    // Sanitize input to prevent basic SQL injection
    $safe_email = mysqli_real_escape_string($connection, $email);

    $query = "SELECT id, name, email, password, role FROM users WHERE email = '$safe_email' LIMIT 1";
    $result = mysqli_query($connection, $query);
    
    // If no user is found with this email
    if (mysqli_num_rows($result) === 0) {
        return false;
    }
    
    $user = mysqli_fetch_assoc($result);
    
    // Verify password (plain text comparison per project constraints)
    if ($password === $user['password']) {
        // Establish session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    
    return false;
}

/**
 * Registers a new user in the database.
 */
function registerUser($name, $email, $password) {
    global $connection;
    
    // Sanitize inputs
    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_email = mysqli_real_escape_string($connection, $email);
    $safe_password = mysqli_real_escape_string($connection, $password);
    
    // Check if the email is already registered
    $checkQuery = "SELECT id FROM users WHERE email = '$safe_email'";
    $checkResult = mysqli_query($connection, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        return false; // Email already exists
    }

    // Insert the new user with default 'user' role
    $query = "INSERT INTO users (name, email, password, role) VALUES ('$safe_name', '$safe_email', '$safe_password', 'user')";
    
    if (mysqli_query($connection, $query)) {
        return true;
    }
    
    return false;
}

/**
 * Terminates the current user session.
 */
function logoutUser() {
    $_SESSION = array();
    session_destroy();
}

/**
 * Checks if any user is currently logged in.
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Checks if the logged-in user possesses Administrator privileges.
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>