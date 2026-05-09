<?php
/* BACKEND REGISTRATION HANDLER
 * Processes new account sign-ups from the HomeFix auth.php interface.
 */

require_once 'auth.php';

// Ensure the request is an active form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Retrieve and trim inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate password confirmation match
    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match. Please verify your input.";
        header("Location: ../auth.php");
        exit;
    }
    
    // Validate all fields are populated
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['register_error'] = "All registration fields are strictly required.";
        header("Location: ../auth.php");
        exit;
    }
    
    // Execute database insertion
    if (registerUser($name, $email, $password)) {
        // Automatically authenticate the user upon successful record creation
        loginUser($email, $password);
        
        // Route standard users to the primary application interface
        header("Location: ../index.php");
        exit;
    } else {
        // Registration failure triggers when the email constraint is violated
        $_SESSION['register_error'] = "This email address is already associated with an account. Please proceed to login.";
        header("Location: ../auth.php");
        exit;
    }
}

// Fallback: Redirect invalid access methods to the authentication portal
header("Location: ../auth.php");
exit;
?>