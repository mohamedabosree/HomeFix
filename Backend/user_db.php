<?php


require_once '../backend/db.php';


function getUserById($id) {
    global $connection;
    $safe_id = mysqli_real_escape_string($connection, $id);
    
    $query = "SELECT u.id, u.name, u.email, u.role, u.created_at, u.location_id, l.city, l.street_name 
              FROM users u 
              LEFT JOIN locations l ON u.location_id = l.location_id 
              WHERE u.id = '$safe_id' LIMIT 1";
              
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}


function updateUser($id, $name, $email, $password = '', $city = '', $street = '') {
    global $connection;
    $safe_id = mysqli_real_escape_string($connection, $id);
    $safe_name = mysqli_real_escape_string($connection, $name);
    $safe_email = mysqli_real_escape_string($connection, $email);

   
    $user = getUserById($id);
    $loc_id = $user['location_id'];

    if (!empty($city) || !empty($street)) {
        $safe_city = mysqli_real_escape_string($connection, $city);
        $safe_street = mysqli_real_escape_string($connection, $street);
        
        if ($loc_id) {
            
            mysqli_query($connection, "UPDATE locations SET city = '$safe_city', street_name = '$safe_street' WHERE location_id = '$loc_id'");
        } else {
            
            mysqli_query($connection, "INSERT INTO locations (city, street_name) VALUES ('$safe_city', '$safe_street')");
            $new_loc = mysqli_insert_id($connection);
            mysqli_query($connection, "UPDATE users SET location_id = '$new_loc' WHERE id = '$safe_id'");
        }
    }

    $query = "UPDATE users SET name = '$safe_name', email = '$safe_email'";
    if (!empty($password)) {
        $safe_pass = mysqli_real_escape_string($connection, $password);
        $query .= ", password = '$safe_pass'";
    }
    $query .= " WHERE id = '$safe_id'";
    
    return mysqli_query($connection, $query);
}
?>
