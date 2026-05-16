<?php
/* BACKEND BOOKING HANDLER
 * Processes service requests and initializes financial tracking.
 */

require_once 'auth.php';
require_once 'db.php';

if (!isLoggedIn()) {
    header("Location: ../frontend/auth.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_id = $_SESSION['user_id'];
    $service_id = $_POST['service_id'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $booking_date = $_POST['booking_date'] ?? '';
    $problem_description = trim($_POST['problem_description'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'cash';

    if (empty($service_id) || empty($phone) || empty($booking_date) || empty($problem_description)) {
        $_SESSION['booking_error'] = "All booking parameters are mandatory.";
        header("Location: ../frontend/book.php");
        exit;
    }

    if ($booking_date < date('Y-m-d')) {
        $_SESSION['booking_error'] = "Invalid schedule parameter: Date cannot be historical.";
        header("Location: ../frontend/book.php");
        exit;
    }

    global $connection;
    $safe_user = mysqli_real_escape_string($connection, $user_id);
    $safe_service = mysqli_real_escape_string($connection, $service_id);
    $safe_phone = mysqli_real_escape_string($connection, $phone);
    $safe_date = mysqli_real_escape_string($connection, $booking_date);
    $safe_desc = mysqli_real_escape_string($connection, $problem_description);
    $safe_method = mysqli_real_escape_string($connection, $payment_method);

    // ERD Requirement: Retrieve exact service price for the payments ledger
    $price_query = mysqli_query($connection, "SELECT price FROM services WHERE id = '$safe_service'");
    $service_data = mysqli_fetch_assoc($price_query);
    $amount = $service_data['price'] ?? 0;

    // Transaction Phase 1: Inject Booking
    $query = "INSERT INTO bookings (user_id, service_id, phone, booking_date, problem_description, status) 
              VALUES ('$safe_user', '$safe_service', '$safe_phone', '$safe_date', '$safe_desc', 'pending')";

    if (mysqli_query($connection, $query)) {
        $booking_id = mysqli_insert_id($connection);
        
        // Transaction Phase 2: Inject Financial Record
        $pay_query = "INSERT INTO payments (booking_id, amount, payment_method, status) 
                      VALUES ('$booking_id', '$amount', '$safe_method', 'pending')";
        mysqli_query($connection, $pay_query);

        $_SESSION['last_booking_id'] = $booking_id;
        header("Location: ../frontend/success.php");
        exit;
    } else {
        $_SESSION['booking_error'] = "Critical database execution failure. Please retry.";
        header("Location: ../frontend/book.php");
        exit;
    }
}

header("Location: ../frontend/book.php");
exit;
?>