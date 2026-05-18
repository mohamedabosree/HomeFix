<?php


require_once '../backend/auth.php';
require_once '../backend/db.php';


if (!isAdmin()) {
    
    header("Location: ../Frontend/auth.php"); 
    exit;
}

global $connection;
$error = ''; $success = '';


if (isset($_GET['toggle_role']) && is_numeric($_GET['toggle_role'])) {
    $id = (int)$_GET['toggle_role'];
    if ($id == $_SESSION['user_id']) {
        $error = "Protocol Error: You cannot demote yourself.";
    } else {
        $user_res = mysqli_query($connection, "SELECT role FROM users WHERE id = $id");
        $user_data = mysqli_fetch_assoc($user_res);
        $new_role = ($user_data['role'] === 'admin') ? 'user' : 'admin';
        
        if (mysqli_query($connection, "UPDATE users SET role = '$new_role' WHERE id = $id")) {
            $success = "User access level updated to " . strtoupper($new_role);
        }
    }
}


if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id == $_SESSION['user_id']) {
        $error = "Protocol Error: Administrators cannot purge their active session.";
    } else {
        if (mysqli_query($connection, "DELETE FROM users WHERE id = $id")) {
            $success = "Account and associated data permanently purged.";
        }
    }
}


$users_query = "SELECT u.*, l.city, l.street_name 
                FROM users u 
                LEFT JOIN locations l ON u.id = l.user_id 
                ORDER BY u.created_at DESC";
$users = mysqli_query($connection, $users_query);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | HomeFix Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../Frontend/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="index.php">HomeFix Admin</a>
            <div class="ms-auto d-flex gap-3 align-items-center">
                <span class="text-white-50 small">Admin: <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                <a href="../backend/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-white min-vh-100 shadow-sm py-4">
                <div class="nav flex-column nav-pills me-3">
                    <a class="nav-link text-dark mb-2" href="index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link text-dark mb-2" href="manage_services.php"><i class="bi bi-tools me-2"></i> Services</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_users.php"><i class="bi bi-people me-2"></i> Users</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">User Database CRUD</h1>

                <?php if ($success): ?><div class="alert alert-success border-0 shadow-sm"><?php echo $success; ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert alert-danger border-0 shadow-sm"><?php echo $error; ?></div><?php endif; ?>

                <div class="card border-0 shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Identity</th>
                                <th>Location Anchor</th>
                                <th>Role</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($u = mysqli_fetch_assoc($users)): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold"><?php echo htmlspecialchars($u['name']); ?></div>
                                        <small class="text-secondary"><?php echo htmlspecialchars($u['email']); ?></small>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark"><?php echo htmlspecialchars($u['city'] ?? 'Cairo'); ?></div>
                                        <div class="small text-secondary"><?php echo htmlspecialchars($u['street_name'] ?? 'N/A'); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $u['role'] === 'admin' ? 'bg-danger' : 'bg-success'; ?>">
                                            <?php echo strtoupper($u['role']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                            <a href="?toggle_role=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-dark">Toggle Role</a>
                                            <a href="?delete=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Purge user?')">Delete</a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border">Active Admin</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
