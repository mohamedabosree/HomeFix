<?php
/* BACKEND PROFILE UPDATE HANDLER
 * Processes modifications to user identity details and geographic anchors.
 * Path: HomeFix/backend/profile_handler.php
 */

require_once 'auth.php';
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Strictly block unauthenticated access
if (!isLoggedIn()) {
    header("Location: ../Frontend/auth.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $connection;
    $u_id = (int)$_SESSION['user_id'];

    // Capture and sanitize incoming datasets
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $street = trim($_POST['street'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation checks
    if (empty($name) || empty($email) || empty($city) || empty($street)) {
        $_SESSION['profile_error'] = "Identity, city, and street name fields cannot be empty.";
        header("Location: ../Frontend/profile.php");
        exit;
    }

    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_email = mysqli_real_escape_string($connection, $email);
    $safe_city = mysqli_real_escape_string($connection, $city);
    $safe_street = mysqli_real_escape_string($connection, $street);

    // Step 1: Update Core User Table Identity Fields
    $user_query = "UPDATE users SET name = '$safe_name', email = '$safe_email'";

    // Process optional security update if provided
    if (!empty($password)) {
        if ($password !== $confirm_password) {
            $_SESSION['profile_error'] = "Security update failed: Passwords do not match.";
            header("Location: ../Frontend/profile.php");
            exit;
        }
        $safe_pass = mysqli_real_escape_string($connection, $password);
        $user_query .= ", password = '$safe_pass'";
    }

    $user_query .= " WHERE id = '$u_id'";
    
    if (mysqli_query($connection, $user_query)) {
        // Synchronize updated session parameters
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;

        // Step 2: Update Geographic Anchor Table using user_id relation
        $loc_check = mysqli_query($connection, "SELECT location_id FROM locations WHERE user_id = '$u_id'");
        
        if (mysqli_num_rows($loc_check) > 0) {
            $loc_query = "UPDATE locations SET city = '$safe_city', street_name = '$safe_street' WHERE user_id = '$u_id'";
        } else {
            $loc_query = "INSERT INTO locations (user_id, city, street_name) VALUES ('$u_id', '$safe_city', '$safe_street')";
        }

        if (mysqli_query($connection, $loc_query)) {
            $_SESSION['profile_success'] = "Profile configuration and geographic parameters updated successfully.";
        } else {
            $_SESSION['profile_error'] = "Identity saved, but location parameters failed to update.";
        }
    } else {
        $_SESSION['profile_error'] = "System error: Email might already be assigned to another account.";
    }

    header("Location: ../Frontend/profile.php");
    exit;
}

header("Location: ../Frontend/profile.php");
exit;
?>