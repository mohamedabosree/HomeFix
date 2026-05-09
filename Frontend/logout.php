<?php
/* FRONTEND LOGOUT HANDLER
 * Terminates the active user session securely and clears all authentication data.
 */

// Require the core authentication logic from the backend
require_once '../backend/auth.php';

// Execute the session termination function
logoutUser();

// Redirect the user back to the unified authentication portal in the root directory
header("Location: ../auth.php");
exit;
?>