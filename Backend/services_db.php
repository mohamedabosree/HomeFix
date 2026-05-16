<?php
/* BACKEND SERVICES DATABASE MODULE
 * Handles data retrieval for the HomeFix service catalog.
 * Updated for the 10-Table ERD Architecture.
 */

// Require the database connection
require_once __DIR__ . '/db.php';

/**
 * Retrieves all available services joined with their category taxonomy.
 * Used to populate the frontend service catalog and booking dropdowns.
 * * @return array An array of associative arrays containing categorized service data.
 */
function getAllServices() {
    global $connection;
    
    // Select required columns and resolve the category_id via LEFT JOIN
    $query = "SELECT 
                s.id, 
                s.name, 
                s.description, 
                s.price, 
                s.image_icon, 
                s.category_id,
                c.category_name 
              FROM services s
              LEFT JOIN categories c ON s.category_id = c.category_id
              ORDER BY c.category_name ASC, s.name ASC";
              
    $result = mysqli_query($connection, $query);
    
    $services = array();
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }
    
    return $services;
}

/**
 * Retrieves a specific service by its ID, including its category.
 * * @param int $id The ID of the service to retrieve.
 * @return array|null An associative array of the service data, or null if not found.
 */
function getServiceById($id) {
    global $connection;

    $safe_id = mysqli_real_escape_string($connection, $id);
    
    $query = "SELECT 
                s.id, 
                s.name, 
                s.description, 
                s.price, 
                s.image_icon, 
                s.category_id,
                c.category_name 
              FROM services s
              LEFT JOIN categories c ON s.category_id = c.category_id
              WHERE s.id = '$safe_id' 
              LIMIT 1";
              
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}
?>