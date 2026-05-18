<?php


require_once '../backend/auth.php';
require_once '../backend/db.php';


if (!isAdmin()) {
   
    header("Location: ../Frontend/auth.php"); 
    exit;
}

global $connection;


$query = "SELECT 
            w.warranty_id, w.start_date, w.end_date,
            b.id as booking_id, b.status,
            u.name as user_name,
            s.name as service_name
          FROM warranties w
          JOIN bookings b ON w.booking_id = b.id
          JOIN users u ON b.user_id = u.id
          JOIN services s ON b.service_id = s.id
          ORDER BY w.warranty_id DESC";

$result = mysqli_query($connection, $query);
$warranties = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) { $warranties[] = $row; }
}

$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Manage Warranties | HomeFix Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4"><a class="navbar-brand fw-bold" href="index.php">HomeFix Admin</a></div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-white min-vh-100 shadow-sm py-4">
                <div class="nav flex-column nav-pills me-3">
                    <a class="nav-link text-dark mb-2" href="index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link text-dark mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                    <a class="nav-link text-dark mb-2" href="manage_payments.php"><i class="bi bi-credit-card me-2"></i> Payments</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_warranties.php"><i class="bi bi-shield-check me-2"></i> Warranties</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Service Warranty Ledger</h1>

                <div class="card border-0 shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="ps-4">Reference</th><th>Client</th><th>Service Rendered</th><th>Start Date</th><th>Expiration</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php if (empty($warranties)): ?>
                                <tr><td colspan="6" class="text-center py-4">No warranty data generated.</td></tr>
                            <?php else: ?>
                                <?php foreach ($warranties as $w): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-secondary">WRT-<?php echo str_pad($w['warranty_id'], 5, "0", STR_PAD_LEFT); ?></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($w['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($w['service_name']); ?> <br><small class="text-secondary">Booking #<?php echo $w['booking_id']; ?></small></td>
                                        <td><?php echo date('M d, Y', strtotime($w['start_date'])); ?></td>
                                        <td class="fw-bold"><?php echo date('M d, Y', strtotime($w['end_date'])); ?></td>
                                        <td>
                                            <?php if ($w['end_date'] < $today): ?>
                                                <span class="badge bg-danger">Expired</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Active Guarantee</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
