<?php
/* HOMEFIX - USER MANAGEMENT
 * Audit and control panel for registered platform users.
 */

require_once '../backend/auth.php';
require_once '../backend/admin_db.php';

if (!isAdmin()) {
    header("Location: ../auth.php");
    exit;
}

$error = '';
$success = '';

// Process Deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    if ($_GET['delete'] == $_SESSION['user_id']) {
        $error = "Protocol violation: An administrator cannot terminate their active session account.";
    } else {
        if (deleteUser($_GET['delete'])) {
            $success = "User account permanently purged from the database.";
        } else {
            $error = "System error: Failed to execute account deletion.";
        }
    }
}

$users = getAllUsers();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | HomeFix</title>
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
                    <a class="nav-link text-dark mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_users.php"><i class="bi bi-people me-2"></i> Users</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Account Database</h1>

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
                                        <th>Name</th>
                                        <th>Email Record</th>
                                        <th>Access Level</th>
                                        <th>Registration Date</th>
                                        <th class="text-end pe-4">System Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($users)): ?>
                                        <tr><td colspan="6" class="text-center py-4 text-secondary">Database empty.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-secondary">#<?php echo htmlspecialchars($user['id']); ?></td>
                                                <td class="fw-bold"><?php echo htmlspecialchars($user['name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td>
                                                    <?php if ($user['role'] === 'admin'): ?>
                                                        <span class="badge bg-danger">Administrator</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">User</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-secondary small"><?php echo htmlspecialchars(date('M d, Y', strtotime($user['created_at']))); ?></td>
                                                <td class="text-end pe-4">
                                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                        <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('WARNING: This will permanently purge the user data. Proceed?')">Purge</a>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-secondary border">Active Session</span>
                                                    <?php endif; ?>
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