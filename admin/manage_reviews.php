<?php
/* HOMEFIX ADMIN - REVIEW MANAGEMENT
 * Moderation board for customer feedback and quality assurance.
 */

require_once '../backend/auth.php';
require_once '../backend/db.php';

// Find this section at the top of your admin files:
if (!isAdmin()) {
    // Change this line:
    header("Location: ../Frontend/auth.php"); 
    exit;
}

global $connection;
$success = '';

// Execute deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($connection, "DELETE FROM reviews WHERE review_id = '$id'");
    $success = "Review purged from the database.";
}

// Map reviews to users and specific service transactions
$query = "SELECT 
            r.review_id, r.rating, r.comment,
            b.id as booking_id,
            u.name as user_name,
            s.name as service_name
          FROM reviews r
          JOIN bookings b ON r.booking_id = b.id
          JOIN users u ON r.user_id = u.id
          JOIN services s ON b.service_id = s.id
          ORDER BY r.review_id DESC";

$result = mysqli_query($connection, $query);
$reviews = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) { $reviews[] = $row; }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Manage Reviews | HomeFix Admin</title>
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
                    <a class="nav-link text-dark mb-2" href="manage_technicians.php"><i class="bi bi-person-badge me-2"></i> Technicians</a>
                    <a class="nav-link text-dark mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_reviews.php"><i class="bi bi-star me-2"></i> Reviews</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Feedback Moderation</h1>

                <?php if ($success): ?><div class="alert alert-success border-0 shadow-sm"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

                <div class="card border-0 shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="ps-4">ID</th><th>Client</th><th>Service Context</th><th>Rating</th><th>Comment</th><th class="text-end pe-4">Action</th></tr></thead>
                        <tbody>
                            <?php if (empty($reviews)): ?>
                                <tr><td colspan="6" class="text-center py-4">No reviews submitted.</td></tr>
                            <?php else: ?>
                                <?php foreach ($reviews as $r): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-secondary">#<?php echo $r['review_id']; ?></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($r['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($r['service_name']); ?></td>
                                        <td class="text-warning">
                                            <?php echo str_repeat('<i class="bi bi-star-fill"></i>', $r['rating']) . str_repeat('<i class="bi bi-star"></i>', 5 - $r['rating']); ?>
                                        </td>
                                        <td><p class="small text-secondary mb-0" style="max-width: 300px;"><?php echo htmlspecialchars($r['comment']); ?></p></td>
                                        <td class="text-end pe-4"><a href="?delete=<?php echo $r['review_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Purge this review?');">Drop</a></td>
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