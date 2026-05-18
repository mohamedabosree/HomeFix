<?php


require_once '../backend/auth.php';
require_once '../backend/db.php';


if (!isAdmin()) {
    header("Location: ../Frontend/auth.php"); 
    exit;
}

global $connection;


$user_count = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM users"))['total'];

$tech_count = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM technicians"))['total'];

$revenue = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(price) as total FROM services s JOIN bookings b ON s.id = b.service_id"))['total'] ?? 0;

$pending_pay = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM payments WHERE status = 'pending'"))['total'];

$active_warranties = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM warranties WHERE end_date >= CURDATE()"))['total'];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | HomeFix Suite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../Frontend/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">HomeFix Admin</a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-white-50 small">Admin: <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                <a href="../backend/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h6 class="text-secondary fw-bold text-uppercase small">Revenue</h6>
                    <h2 class="fw-bold mb-0 text-success"><?php echo number_format($revenue); ?> EGP</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h6 class="text-secondary fw-bold text-uppercase small">Workforce</h6>
                    <h2 class="fw-bold mb-0 text-primary"><?php echo $tech_count; ?> Techs</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h6 class="text-secondary fw-bold text-uppercase small">Pending Pay</h6>
                    <h2 class="fw-bold mb-0 text-warning"><?php echo $pending_pay; ?> Invoices</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h6 class="text-secondary fw-bold text-uppercase small">Active Warranties</h6>
                    <h2 class="fw-bold mb-0 text-info"><?php echo $active_warranties; ?> Guarantees</h2>
                </div>
            </div>
        </div>

        <h4 class="fw-bold mb-4">Management Modules</h4>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-uppercase text-secondary mb-3 small"><i class="bi bi-people-fill me-2"></i>Staff & Users</h6>
                        <div class="list-group list-group-flush">
                            <a href="manage_users.php" class="list-group-item list-group-item-action border-0 px-0">Manage User Records</a>
                            <a href="manage_technicians.php" class="list-group-item list-group-item-action border-0 px-0">Technician Roster</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-uppercase text-secondary mb-3 small"><i class="bi bi-tools me-2"></i>Service Catalog</h6>
                        <div class="list-group list-group-flush">
                            <a href="manage_categories.php" class="list-group-item list-group-item-action border-0 px-0">Category Taxonomy</a>
                            <a href="manage_services.php" class="list-group-item list-group-item-action border-0 px-0">Price & Catalog Updates</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-uppercase text-secondary mb-3 small"><i class="bi bi-calendar-check-fill me-2"></i>Operations</h6>
                        <div class="list-group list-group-flush">
                            <a href="manage_bookings.php" class="list-group-item list-group-item-action border-0 px-0">Dispatch Board</a>
                            <a href="view_bookings.php" class="list-group-item list-group-item-action border-0 px-0">Full Transaction Logs</a>
                            <a href="manage_warranties.php" class="list-group-item list-group-item-action border-0 px-0">Service Warranties</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-uppercase text-secondary mb-3 small"><i class="bi bi-graph-up-arrow me-2"></i>Audit & Quality</h6>
                        <div class="list-group list-group-flush">
                            <a href="manage_payments.php" class="list-group-item list-group-item-action border-0 px-0">Financial Ledger</a>
                            <a href="manage_reviews.php" class="list-group-item list-group-item-action border-0 px-0">Review Moderation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
