<?php
/* BACKEND SERVICES DATABASE MODULE
 * Handles data retrieval for the HomeFix service catalog.
 */

// Require the database connection
require_once 'db.php';

/**
 * Retrieves all available services from the database.
 * Used to populate the frontend service catalog and booking dropdowns.
 * * @return array An array of associative arrays containing service data.
 */
function getAllServices() {
    global $connection;
    
    // Select all required columns, ordered by ID
    $query = "SELECT id, name, description, price, image_icon FROM services ORDER BY id";
    $result = mysqli_query($connection, $query);
    
    $services = array();
    
    // Loop through results and append to the array
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }
    
    return $services;
}

/**
 * Retrieves a specific service by its ID.
 * * @param int $id The ID of the service to retrieve.
 * @return array|null An associative array of the service data, or null if not found.
 */
function getServiceById($id) {
    global $connection;

    // Sanitize the ID to prevent SQL injection
    $safe_id = mysqli_real_escape_string($connection, $id);
    
    $query = "SELECT id, name, description, price, image_icon FROM services WHERE id = '$safe_id' LIMIT 1";
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}
?>