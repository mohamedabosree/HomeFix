<?php
/* BACKEND ADMIN DATABASE MODULE
 * Handles full CRUD operations for the HomeFix administrative panel.
 */

require_once 'db.php';

// ==========================================
// 1. SERVICES MANAGEMENT
// ==========================================

function getAllServicesAdmin() {
    global $connection;
    $query = "SELECT id, name, description, price, image_icon FROM services ORDER BY id DESC";
    $result = mysqli_query($connection, $query);
    $services = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }
    return $services;
}

function addService($name, $description, $price, $image_icon) {
    global $connection;
    
    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_desc = mysqli_real_escape_string($connection, $description);
    $safe_price = (float)$price;
    $safe_icon = mysqli_real_escape_string($connection, $image_icon);
    
    $query = "INSERT INTO services (name, description, price, image_icon) 
              VALUES ('$safe_name', '$safe_desc', '$safe_price', '$safe_icon')";
              
    return mysqli_query($connection, $query);
}

function updateService($id, $name, $description, $price, $image_icon) {
    global $connection;
    
    $safe_id = mysqli_real_escape_string($connection, $id);
    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_desc = mysqli_real_escape_string($connection, $description);
    $safe_price = (float)$price;
    $safe_icon = mysqli_real_escape_string($connection, $image_icon);
    
    $query = "UPDATE services 
              SET name = '$safe_name', description = '$safe_desc', price = '$safe_price', image_icon = '$safe_icon' 
              WHERE id = '$safe_id'";
              
    return mysqli_query($connection, $query);
}

function deleteService($id) {
    global $connection;
    $safe_id = mysqli_real_escape_string($connection, $id);
    $query = "DELETE FROM services WHERE id = '$safe_id'";
    return mysqli_query($connection, $query);
}

// ==========================================
// 2. USER MANAGEMENT
// ==========================================

function getAllUsers() {
    global $connection;
    $query = "SELECT id, name, email, role, created_at FROM users ORDER BY id DESC";
    $result = mysqli_query($connection, $query);
    $users = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
    return $users;
}

function deleteUser($id) {
    global $connection;
    $safe_id = mysqli_real_escape_string($connection, $id);
    $query = "DELETE FROM users WHERE id = '$safe_id'";
    return mysqli_query($connection, $query);
}

// ==========================================
// 3. BOOKINGS MANAGEMENT
// ==========================================

function getAllBookings() {
    global $connection;
    
    // JOIN query to retrieve booking details alongside user and service information
    $query = "SELECT 
                b.id, 
                b.user_id, 
                u.name as user_name,
                b.service_id,
                s.name as service_name,
                s.price,
                b.phone,
                b.booking_date,
                b.problem_description,
                b.status,
                b.created_at
              FROM bookings b
              JOIN users u ON b.user_id = u.id
              JOIN services s ON b.service_id = s.id
              ORDER BY b.id DESC";
              
    $result = mysqli_query($connection, $query);
    $bookings = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
    }
    return $bookings;
}

function updateBookingStatus($id, $status) {
    global $connection;
    
    $safe_id = mysqli_real_escape_string($connection, $id);
    $safe_status = mysqli_real_escape_string($connection, $status);
    
    $query = "UPDATE bookings SET status = '$safe_status' WHERE id = '$safe_id'";    
    return mysqli_query($connection, $query);
}
?>