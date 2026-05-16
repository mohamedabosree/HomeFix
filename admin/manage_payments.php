<?php
/* HOMEFIX ADMIN - PAYMENT MANAGEMENT
 * Financial audit board for clearing and tracking service transactions.
 */

require_once '../backend/auth.php';
require_once '../backend/admin_db.php';
require_once '../backend/db.php';

// Find this section at the top of your admin files:
if (!isAdmin()) {
    // Change this line:
    header("Location: ../Frontend/auth.php"); 
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'], $_POST['status'])) {
    global $connection;
    $p_id = (int)$_POST['payment_id'];
    $status = mysqli_real_escape_string($connection, $_POST['status']);
    
    if (mysqli_query($connection, "UPDATE payments SET status = '$status' WHERE payment_id = '$p_id'")) {
        $success = "Financial ledger updated.";
    } else {
        $error = "Ledger update failed.";
    }
}

global $connection;
$payments = [];
if ($result = mysqli_query($connection, "SELECT * FROM payments ORDER BY payment_id DESC")) {
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments | HomeFix Admin</title>
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
                    <a class="nav-link text-dark mb-2" href="manage_categories.php"><i class="bi bi-tags me-2"></i> Categories</a>
                    <a class="nav-link text-dark mb-2" href="manage_services.php"><i class="bi bi-tools me-2"></i> Services</a>
                    <a class="nav-link text-dark mb-2" href="manage_technicians.php"><i class="bi bi-person-badge me-2"></i> Technicians</a>
                    <a class="nav-link text-dark mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_payments.php"><i class="bi bi-credit-card me-2"></i> Payments</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Financial Ledger</h1>

                <?php if ($success): ?><div class="alert alert-success border-0 shadow-sm"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert alert-danger border-0 shadow-sm"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

                <div class="card border-0 shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="ps-4">Trans. ID</th><th>Booking Ref</th><th>Client</th><th>Amount</th><th>Method</th><th class="text-end pe-4">Clearance Status</th></tr></thead>
                        <tbody>
                            <?php foreach ($payments as $pay): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">TRX-<?php echo str_pad($pay['payment_id'], 5, "0", STR_PAD_LEFT); ?></td>
                                    <td>#<?php echo $pay['booking_id']; ?></td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($pay['user_name']); ?></td>
                                    <td class="text-primary fw-bold"><?php echo number_format($pay['amount'], 2); ?> EGP</td>
                                    <td><span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($pay['payment_method']); ?></span></td>
                                    <td class="text-end pe-4">
                                        <form method="POST" class="d-flex justify-content-end gap-2">
                                            <input type="hidden" name="payment_id" value="<?php echo $pay['payment_id']; ?>">
                                            <select name="status" class="form-select form-select-sm w-auto">
                                                <option value="pending" <?php echo $pay['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="successful" <?php echo $pay['status'] === 'successful' ? 'selected' : ''; ?>>Successful</option>
                                                <option value="failed" <?php echo $pay['status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-dark">Update</button>
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