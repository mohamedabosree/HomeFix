<?php
/* BACKEND USER DATABASE MODULE
 * Handles user profile data retrieval and updates for the HomeFix platform.
 */

// Require the central database connection
require_once 'db.php';

/**
 * Retrieves a user's complete profile information by their ID.
 * * @param int $id The user ID to look up.
 * @return array|null Returns an associative array of user data, or null if not found.
 */
function getUserById($id) {
    global $connection;
    
    // Sanitize the ID to prevent SQL injection
    $safe_id = mysqli_real_escape_string($connection, $id);
    
    // Select user information, excluding the password for security
    $query = "SELECT id, name, email, role, created_at FROM users WHERE id = '$safe_id' LIMIT 1";
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

/**
 * Updates a user's profile information.
 * * @param int $id The user ID to update.
 * @param string $name The updated name.
 * @param string $email The updated email.
 * @param string $password The updated password (optional).
 * @return bool Returns true if the update was successful, false otherwise.
 */
function updateUser($id, $name, $email, $password = '') {
    global $connection;
    
    // Sanitize all inputs
    $safe_id = mysqli_real_escape_string($connection, $id);
    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_email = mysqli_real_escape_string($connection, $email);
    
    // Build the core UPDATE query
    $query = "UPDATE users SET name = '$safe_name', email = '$safe_email'";
    
    // Conditionally append the password update if a new password was provided
    if (!empty($password)) {
        $safe_password = mysqli_real_escape_string($connection, $password);
        $query .= ", password = '$safe_password'";
    }
    
    // Target the specific user
    $query .= " WHERE id = '$safe_id'";
    
    // Execute the query
    if (mysqli_query($connection, $query)) {
        return true;
    }
    
    return false;
}
?>