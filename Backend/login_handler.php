<?php
/* BACKEND LOGIN HANDLER
 * Processes form submissions from the HomeFix auth.php page.
 */

require_once 'auth.php';

// Ensure the request is an active form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Retrieve and trim inputs
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Execute login validation
    if (loginUser($email, $password)) {
        
        // Route the user based on their database role
        if (isAdmin()) {
            header("Location: ../admin/index.php");
        } else {
            // Redirect standard users to the main application root
            header("Location: ../index.php");
        }
        exit;
        
    } else {
        // Authentication failed: Set session error and return to auth interface
        $_SESSION['login_error'] = "Invalid email address or password. Please try again.";
        header("Location: ../auth.php");
        exit;
    }
}

// Fallback: Redirect direct URL accesses back to the authentication portal
header("Location: ../auth.php");
exit;
?>