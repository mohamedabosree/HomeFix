<?php
/* HOMEFIX - BOOKING MANAGEMENT
 * Dispatch board for reviewing service requests and updating operational status.
 */

require_once '../backend/auth.php';
require_once '../backend/admin_db.php';

if (!isAdmin()) {
    header("Location: ../auth.php");
    exit;
}

$error = '';
$success = '';

// Process Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    if (updateBookingStatus($_POST['booking_id'], $_POST['status'])) {
        $success = "Dispatch status successfully updated.";
    } else {
        $error = "System error: Status update failed.";
    }
}

$bookings = getAllBookings();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | HomeFix</title>
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
                    <a class="nav-link text-dark mb-2" href="index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link text-dark mb-2" href="manage_services.php"><i class="bi bi-tools me-2"></i> Services</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                    <a class="nav-link text-dark mb-2" href="manage_users.php"><i class="bi bi-people me-2"></i> Users</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Operational Dispatch Board</h1>

                <?php if ($success): ?>
                    <div class="alert alert-success border-0 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger border-0 shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Client Details</th>
                                        <th>Service Required</th>
                                        <th>Scheduled Date</th>
                                        <th>Status Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($bookings)): ?>
                                        <tr><td colspan="5" class="text-center py-4 text-secondary">No active bookings in the system.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($bookings as $booking): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-secondary">#<?php echo htmlspecialchars($booking['id']); ?></td>
                                                <td>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($booking['user_name']); ?></div>
                                                    <div class="small text-secondary"><i class="bi bi-telephone-fill me-1"></i><?php echo htmlspecialchars($booking['phone']); ?></div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-primary"><?php echo htmlspecialchars($booking['service_name']); ?></div>
                                                    <div class="small text-secondary text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($booking['problem_description']); ?>">
                                                        <?php echo htmlspecialchars($booking['problem_description']); ?>
                                                    </div>
                                                </td>
                                                <td class="fw-bold"><?php echo htmlspecialchars(date('M d, Y', strtotime($booking['booking_date']))); ?></td>
                                                <td class="pe-4">
                                                    <form method="POST" class="d-flex gap-2">
                                                        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['id']); ?>">
                                                        <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                                            <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="completed" <?php echo $booking['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                            <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-dark">Update</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>