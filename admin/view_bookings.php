<?php
require_once '../backend/auth.php';
require_once '../backend/db.php';
// Find this section at the top of your admin files:
if (!isAdmin()) {
    // Change this line:
    header("Location: ../Frontend/auth.php"); 
    exit;
}

$bookings = mysqli_query($connection, "SELECT b.*, u.name as u_name, s.name as s_name FROM bookings b JOIN users u ON b.user_id = u.id JOIN services s ON b.service_id = s.id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Logs | HomeFix Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Frontend/style.css">
</head>
<body class="bg-light py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Transaction Logs</h2>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <table class="table mb-0">
                <thead class="table-dark">
                    <tr><th>Customer</th><th>Service</th><th>Status</th><th>Update</th></tr>
                </thead>
                <tbody>
                    <?php while($b = mysqli_fetch_assoc($bookings)): ?>
                    <tr>
                        <td><?php echo $b['u_name']; ?></td>
                        <td><?php echo $b['s_name']; ?></td>
                        <td><span class="badge bg-info"><?php echo $b['status']; ?></span></td>
                        <td>
                            <form method="POST" action="../backend/admin_handlers.php" class="d-flex gap-2">
                                <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <button name="update_booking" class="btn btn-sm btn-dark">Save</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <a href="index.php" class="btn btn-link mt-3">Back to Dashboard</a>
    </div>
</body>
</html>