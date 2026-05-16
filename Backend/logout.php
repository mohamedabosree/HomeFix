<?php
/* BACKEND LOGOUT HANDLER
 * Path: HomeFix/backend/logout.php
 */

// Step 1: Access the authentication logic (same directory)
require_once 'auth.php';

// Step 2: Clear the session data
logoutUser();

// Step 3: Redirect back to the login page in the Frontend folder
header("Location: ../Frontend/auth.php");
exit;
?>