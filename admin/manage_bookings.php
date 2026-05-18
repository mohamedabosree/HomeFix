<?php


require_once '../backend/auth.php';
require_once '../backend/admin_db.php';


if (!isAdmin()) {
   
    header("Location: ../Frontend/auth.php"); 
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    $tech_id = $_POST['tech_id'] ?? null;
    if (updateBookingStatus($_POST['booking_id'], $_POST['status'], $tech_id)) {
        $success = "Dispatch protocol updated successfully. Automated ERD triggers (Warranty/Points) fired if completed.";
    } else {
        $error = "System error during dispatch update.";
    }
}

$bookings = getAllBookings();
$technicians = getAllTechnicians();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings | HomeFix Admin</title>
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
                    <a class="nav-link text-dark mb-2" href="manage_services.php"><i class="bi bi-tools me-2"></i> Services</a>
                    <a class="nav-link text-dark mb-2" href="manage_technicians.php"><i class="bi bi-person-badge me-2"></i> Technicians</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Operational Dispatch Board</h1>

                <?php if ($success): ?><div class="alert alert-success border-0 shadow-sm"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert alert-danger border-0 shadow-sm"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

                <div class="card border-0 shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th><th>Client</th><th>Service</th><th>Assigned Tech</th><th>Date</th><th class="text-end pe-4">Controls</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#<?php echo $booking['id']; ?></td>
                                    <td><div class="fw-bold"><?php echo htmlspecialchars($booking['user_name']); ?></div><small><?php echo htmlspecialchars($booking['phone']); ?></small></td>
                                    <td><div class="fw-bold text-primary"><?php echo htmlspecialchars($booking['service_name']); ?></div></td>
                                    <td>
                                        <form method="POST" class="d-flex flex-column gap-2">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                            <select name="tech_id" class="form-select form-select-sm" <?php echo $booking['status'] === 'completed' ? 'disabled' : ''; ?>>
                                                <option value="">Unassigned</option>
                                                <?php foreach ($technicians as $tech): ?>
                                                    <option value="<?php echo $tech['tech_id']; ?>" <?php echo ($booking['tech_id'] == $tech['tech_id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($tech['name'] . " (" . $tech['specialty'] . ")"); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                    </td>
                                    <td class="fw-bold"><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                                    <td class="text-end pe-4">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <select name="status" class="form-select form-select-sm" style="width: 110px;" <?php echo $booking['status'] === 'completed' ? 'disabled' : ''; ?>>
                                                    <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="completed" <?php echo $booking['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                                <?php if ($booking['status'] !== 'completed'): ?>
                                                    <button type="submit" class="btn btn-sm btn-dark">Commit</button>
                                                <?php else: ?>
                                                    <span class="badge bg-success py-2">Finalized</span>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
