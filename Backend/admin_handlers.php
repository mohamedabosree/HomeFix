<?php

require_once 'auth.php';
require_once 'db.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../Frontend/index.php");
    exit;
}

global $connection;


if (isset($_POST['add_service'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $desc = mysqli_real_escape_string($connection, $_POST['description']);
    $price = (float)$_POST['price'];
    $cat_id = (int)$_POST['category_id'];
    mysqli_query($connection, "INSERT INTO services (category_id, name, description, price) VALUES ('$cat_id', '$name', '$desc', '$price')");
    header("Location: ../admin/manage_services.php?success=added");
}

if (isset($_GET['delete_service'])) {
    $id = (int)$_GET['delete_service'];
    mysqli_query($connection, "DELETE FROM services WHERE id = '$id'");
    header("Location: ../admin/manage_services.php?success=deleted");
}


if (isset($_GET['toggle_role'])) {
    $id = (int)$_GET['toggle_role'];
    $current = mysqli_fetch_assoc(mysqli_query($connection, "SELECT role FROM users WHERE id = '$id'"))['role'];
    $new_role = ($current === 'admin') ? 'user' : 'admin';
    mysqli_query($connection, "UPDATE users SET role = '$new_role' WHERE id = '$id'");
    header("Location: ../admin/manage_users.php?success=role_updated");
}


if (isset($_POST['update_booking'])) {
    $id = (int)$_POST['booking_id'];
    $status = mysqli_real_escape_string($connection, $_POST['status']);
    mysqli_query($connection, "UPDATE bookings SET status = '$status' WHERE id = '$id'");
    header("Location: ../admin/view_bookings.php?success=updated");
}
?>
