<?php
/* FRONTEND BOOKING INTERFACE
 * Captures service requests and financial preferences.
 * Path: HomeFix/Frontend/book.php
 */

require_once '../backend/auth.php';
require_once '../backend/services_db.php';
require_once '../backend/db.php'; // Required to fetch local user context coordinates

// Strictly enforce authentication
if (!isLoggedIn()) {
    $_SESSION['login_error'] = "Authentication required. Please log in to book a service.";
    header("Location: auth.php");
    exit;
}

global $connection;
$client_id = (int)$_SESSION['user_id'];

// Secure geographic retrieval matching the updated normalization schema
$geo_query = "SELECT city, street_name FROM locations WHERE user_id = '$client_id' LIMIT 1";
$geo_result = mysqli_query($connection, $geo_query);
$geo_data = mysqli_fetch_assoc($geo_result);

$client_city = $geo_data['city'] ?? 'Not Configured';
$client_street = $geo_data['street_name'] ?? 'Not Configured';

$services = getAllServices();
$booking_error = $_SESSION['booking_error'] ?? '';
unset($_SESSION['booking_error']);

// Group services by taxonomic category for the dropdown interface
$grouped_services = [];
foreach ($services as $service) {
    $cat = $service['category_name'] ?? 'Uncategorized';
    $grouped_services[$cat][] = $service;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service | HomeFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <header class="navbar-pro shadow-sm sticky-top">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <a class="navbar-brand fw-bold fs-3 text-white text-decoration-none" href="index.php">HomeFix</a>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <a href="profile.php" class="text-light text-decoration-none"><i class="bi bi-person-circle fs-5 me-2"></i><?php echo htmlspecialchars($_SESSION['name']); ?></a>
            </div>
        </div>
    </header>

    <main class="container py-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="text-center mb-5">
                    <h1 class="display-6 fw-bold text-dark">Schedule Maintenance</h1>
                    <p class="text-secondary">Select your requirements. A certified technician will be dispatched to your registered address: <strong class="text-dark"><?php echo htmlspecialchars($client_street . ", " . $client_city); ?></strong>.</p>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        
                        <?php if ($booking_error): ?>
                            <div class="alert alert-danger border-0 shadow-sm py-3 d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>
                                <div><?php echo htmlspecialchars($booking_error); ?></div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="../backend/book_handler.php" class="needs-validation" novalidate>
                            
                            <h2 class="h5 fw-bold text-dark mb-4 border-bottom pb-2">1. Service & Financial Details</h2>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-8">
                                    <label class="form-label small fw-bold text-secondary">Required Service</label>
                                    <select name="service_id" class="form-select form-select-lg bg-light border-0" required>
                                        <option value="" disabled selected>Select from catalog...</option>
                                        <?php foreach ($grouped_services as $category => $items): ?>
                                            <optgroup label="<?php echo htmlspecialchars($category); ?>">
                                                <?php foreach ($items as $item): ?>
                                                    <option value="<?php echo htmlspecialchars($item['id']); ?>">
                                                        <?php echo htmlspecialchars($item['name']); ?> - <?php echo number_format($item['price'], 2); ?> EGP
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-secondary">Payment Method</label>
                                    <select name="payment_method" class="form-select form-select-lg bg-light border-0" required>
                                        <option value="cash" selected>Cash</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="wallet">Digital Wallet</option>
                                    </select>
                                </div>
                            </div>

                            <h2 class="h5 fw-bold text-dark mb-4 border-bottom pb-2 mt-5">2. Dispatch Information</h2>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Preferred Date</label>
                                    <input type="date" name="booking_date" class="form-control form-control-lg bg-light border-0" required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Contact Number</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg bg-light border-0" placeholder="e.g., 01012345678" required>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label small fw-bold text-secondary">Problem Description</label>
                                <textarea name="problem_description" class="form-control bg-light border-0" rows="4" placeholder="Briefly describe the issue..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">Initialize Transaction</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="footer-pro py-5 text-center mt-auto bg-dark">
        <div class="container">
            <div class="navbar-brand fw-bold mb-3 fs-3 text-white">HomeFix</div>
            <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> HomeFix Technician Booking Platform. All rights reserved.</p>            
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>