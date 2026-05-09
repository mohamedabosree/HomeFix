<?php
/* HOMEFIX - ADMIN DASHBOARD
 * Displays platform statistics and operational metrics.
 */

require_once '../backend/auth.php';
require_once '../backend/admin_db.php';

// Access control: Terminate if the user lacks administrator privileges
if (!isAdmin()) {
    header("Location: ../auth.php");
    exit;
}

// Retrieve data arrays
$users = getAllUsers();
$services = getAllServicesAdmin();
$bookings = getAllBookings();

// Calculate system totals
$total_users = count($users);
$total_services = count($services);
$total_bookings = count($bookings);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | HomeFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="index.php">HomeFix Admin</a>
            <div class="d-flex ms-auto gap-3">
                <a href="../index.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i> View Live Site</a>
                <a href="../backend/logout.php" class="btn btn-danger btn-sm"><i class="bi bi-power me-1"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-white min-vh-100 shadow-sm py-4">
                <div class="nav flex-column nav-pills me-3">
                    <a class="nav-link active fw-bold mb-2" href="index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link text-dark mb-2" href="manage_services.php"><i class="bi bi-tools me-2"></i> Services</a>
                    <a class="nav-link text-dark mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                    <a class="nav-link text-dark mb-2" href="manage_users.php"><i class="bi bi-people me-2"></i> Users</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-bold text-dark">System Overview</h1>
                    <span class="text-secondary">Administrator: <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm border-start border-primary border-4 h-100 py-2">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs fw-bold text-primary text-uppercase mb-1">Registered Users</div>
                                    <div class="h3 mb-0 fw-bold text-dark"><?php echo $total_users; ?></div>
                                </div>
                                <i class="bi bi-people fs-1 text-secondary opacity-25"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm border-start border-success border-4 h-100 py-2">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs fw-bold text-success text-uppercase mb-1">Active Services</div>
                                    <div class="h3 mb-0 fw-bold text-dark"><?php echo $total_services; ?></div>
                                </div>
                                <i class="bi bi-tools fs-1 text-secondary opacity-25"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm border-start border-warning border-4 h-100 py-2">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Bookings</div>
                                    <div class="h3 mb-0 fw-bold text-dark"><?php echo $total_bookings; ?></div>
                                </div>
                                <i class="bi bi-calendar-check fs-1 text-secondary opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>