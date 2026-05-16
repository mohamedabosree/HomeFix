<?php
/* BACKEND ADMIN DATABASE MODULE
 * Handles full CRUD operations for the HomeFix administrative panel.
 * Updated for the 10-Table ERD Architecture.
 * Path: HomeFix/backend/admin_db.php
 */

require_once 'db.php';

// ==========================================
// 1. SERVICES MANAGEMENT
// ==========================================

function getAllServicesAdmin() {
    global $connection;
    $query = "SELECT s.id, s.name, s.description, s.price, s.image_icon, c.category_name 
              FROM services s 
              LEFT JOIN categories c ON s.category_id = c.category_id 
              ORDER BY s.id DESC";
    $result = mysqli_query($connection, $query);
    $services = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }
    return $services;
}

function addService($category_id, $name, $description, $price, $image_icon) {
    global $connection;
    $safe_cat = mysqli_real_escape_string($connection, $category_id);
    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_desc = mysqli_real_escape_string($connection, $description);
    $safe_price = (float)$price;
    $safe_icon = mysqli_real_escape_string($connection, $image_icon);
    
    $query = "INSERT INTO services (category_id, name, description, price, image_icon) 
              VALUES ('$safe_cat', '$safe_name', '$safe_desc', '$safe_price', '$safe_icon')";
              
    return mysqli_query($connection, $query);
}

function updateService($id, $category_id, $name, $description, $price, $image_icon) {
    global $connection;
    $safe_id = mysqli_real_escape_string($connection, $id);
    $safe_cat = mysqli_real_escape_string($connection, $category_id);
    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_desc = mysqli_real_escape_string($connection, $description);
    $safe_price = (float)$price;
    $safe_icon = mysqli_real_escape_string($connection, $image_icon);
    
    $query = "UPDATE services 
              SET category_id = '$safe_cat', name = '$safe_name', description = '$safe_desc', 
                  price = '$safe_price', image_icon = '$safe_icon' 
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
    // Structural Fix applied: Join via ON u.id = l.user_id
    $query = "SELECT u.id, u.name, u.email, u.role, u.created_at, l.city, l.street_name 
              FROM users u 
              LEFT JOIN locations l ON u.id = l.user_id 
              ORDER BY u.id DESC";
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
    $query = "SELECT 
                b.id, 
                b.user_id, 
                u.name as user_name,
                b.service_id,
                s.name as service_name,
                s.price,
                b.tech_id,
                t.name as tech_name,
                b.phone,
                b.booking_date,
                b.problem_description,
                b.status,
                b.created_at
              FROM bookings b
              JOIN users u ON b.user_id = u.id
              JOIN services s ON b.service_id = s.id
              LEFT JOIN technicians t ON b.tech_id = t.tech_id
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

function updateBookingStatus($id, $status, $tech_id = null) {
    global $connection;
    $safe_id = mysqli_real_escape_string($connection, $id);
    $safe_status = mysqli_real_escape_string($connection, $status);
    
    $query = "UPDATE bookings SET status = '$safe_status'";
    
    if ($tech_id !== null && $tech_id !== '') {
        $safe_tech = mysqli_real_escape_string($connection, $tech_id);
        $query .= ", tech_id = '$safe_tech'";
    }
    
    $query .= " WHERE id = '$safe_id'";    
    $result = mysqli_query($connection, $query);

    if ($result && $status === 'completed') {
        $res = mysqli_query($connection, "SELECT b.user_id, s.price FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.id = '$safe_id'");
        
        if ($res && mysqli_num_rows($res) > 0) {
            $data = mysqli_fetch_assoc($res);
            $u_id = $data['user_id'];
            $points = floor($data['price'] * 0.1);
            
            // Insert 30-Day Warranty
            $start = date('Y-m-d');
            $end = date('Y-m-d', strtotime('+30 days'));
            mysqli_query($connection, "INSERT INTO warranties (booking_id, start_date, end_date) VALUES ('$safe_id', '$start', '$end')");
            
            // Insert Loyalty Points
            mysqli_query($connection, "INSERT INTO loyalty_points (user_id, booking_id, points_amount) VALUES ('$u_id', '$safe_id', '$points')");
        }
    }
    return $result;
}

// ==========================================
// 4. CATEGORIES & TECHNICIANS MANAGEMENT
// ==========================================

function getAllCategories() {
    global $connection;
    $query = "SELECT * FROM categories ORDER BY category_name ASC";
    $result = mysqli_query($connection, $query);
    $categories = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
    }
    return $categories;
}

function getAllTechnicians() {
    global $connection;
    // Technicians table still maintains location_id as its distinct anchor constraint
    $query = "SELECT t.tech_id, t.name, t.phone, t.specialty, t.availability_status, l.city, l.street_name 
              FROM technicians t
              LEFT JOIN locations l ON t.location_id = l.location_id
              ORDER BY t.name ASC";
    $result = mysqli_query($connection, $query);
    $technicians = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $technicians[] = $row;
        }
    }
    return $technicians;
}
?>