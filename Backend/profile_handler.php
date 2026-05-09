<?php
/* BACKEND PROFILE HANDLER
 * Processes profile update requests from the HomeFix account.php interface.
 */

require_once 'auth.php';
require_once 'user_db.php';

// Verify active session authentication
if (!isLoggedIn()) {
    header("Location: ../auth.php");
    exit;
}

// Process the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Retrieve and trim input data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate mandatory fields
    if (empty($name) || empty($email)) {
        $_SESSION['profile_error'] = "Name and email fields are strictly required.";
        header("Location: ../account.php");
        exit;
    }

    // Validate password confirmation if a password update is requested
    if (!empty($password) && $password !== $confirm_password) {
        $_SESSION['profile_error'] = "The provided passwords do not match. Please verify your input.";
        header("Location: ../account.php");
        exit;
    }

    // Execute the database update operation
    if (updateUser($_SESSION['user_id'], $name, $email, $password)) {
        // Synchronize session variables with the updated database records
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        
        $_SESSION['profile_success'] = "Account profile updated successfully.";
        header("Location: ../account.php");
        exit;
    } else {
        // Handle database operational failures
        $_SESSION['profile_error'] = "A system error occurred. Failed to update profile.";
        header("Location: ../account.php");
        exit;
    }
}

// Fallback: Redirect invalid access methods to the account dashboard
header("Location: ../account.php");
exit;
?>