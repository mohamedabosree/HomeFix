<?php
/* BACKEND REGISTRATION HANDLER
 * Processes new account sign-ups, including geographic anchors.
 */

require_once 'auth.php';
require_once 'db.php'; // Required for global $connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $connection;
    
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $city = trim($_POST['city'] ?? '');
    $street = trim($_POST['street'] ?? '');
    
    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match. Please verify your input.";
        header("Location: ../frontend/auth.php");
        exit;
    }
    
    if (empty($name) || empty($email) || empty($password) || empty($city) || empty($street)) {
        $_SESSION['register_error'] = "All identity and location fields are strictly required.";
        header("Location: ../frontend/auth.php");
        exit;
    }
    
    // Step 1: Create the user account
    $new_user_id = registerUser($name, $email, $password);
    
    if ($new_user_id) {
        // Step 2: Establish the Geographic Anchor mapping to user_id
        $safe_city = mysqli_real_escape_string($connection, $city);
        $safe_street = mysqli_real_escape_string($connection, $street);
        
        $loc_query = "INSERT INTO locations (user_id, city, street_name) 
                      VALUES ('$new_user_id', '$safe_city', '$safe_street')";
        
        if (mysqli_query($connection, $loc_query)) {
            loginUser($email, $password);
            header("Location: ../frontend/index.php");
            exit;
        }
    } else {
        $_SESSION['register_error'] = "This email address is already associated with an active account.";
        header("Location: ../frontend/auth.php");
        exit;
    }
}

header("Location: ../frontend/auth.php");
exit;
?>