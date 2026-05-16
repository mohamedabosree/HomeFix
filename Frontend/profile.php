<?php
/* FRONTEND PROFILE & SETTINGS
 * Displays the user dashboard, location data, and dynamic loyalty points.
 * Path: HomeFix/Frontend/profile.php
 */

require_once '../backend/auth.php';
require_once '../backend/db.php'; // Direct inclusion to leverage global $connection

if (!isLoggedIn()) {
    header("Location: auth.php");
    exit;
}

global $connection;
$u_id = (int)$_SESSION['user_id'];

// --- DYNAMIC DATA RETRIEVAL (WEEK 3 ARCHITECTURE) ---
// Join logic corrected to point strictly to locations.user_id
$user_query = "SELECT u.id, u.name, u.email, u.role, u.phone, l.city, l.street_name 
               FROM users u 
               LEFT JOIN locations l ON u.id = l.user_id 
               WHERE u.id = '$u_id' LIMIT 1";
$user_result = mysqli_query($connection, $user_query);
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    logoutUser();
    header("Location: auth.php");
    exit;
}

// Calculate Dynamic Loyalty Points
$points_query = mysqli_query($connection, "SELECT SUM(points_amount) as total FROM loyalty_points WHERE user_id = '$u_id'");
$points_data = mysqli_fetch_assoc($points_query);
$total_points = $points_data['total'] ?? 0;

$success_msg = $_SESSION['profile_success'] ?? '';
$error_msg = $_SESSION['profile_error'] ?? '';
unset($_SESSION['profile_success'], $_SESSION['profile_error']);

$nameParts = explode(' ', trim($user['name']));
$initials = count($nameParts) >= 2 
    ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1)) 
    : strtoupper(substr($user['name'], 0, 2));
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | HomeFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

    <header class="navbar-pro shadow-sm sticky-top">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <a class="navbar-brand fw-bold fs-3 text-white text-decoration-none" href="index.php">HomeFix</a>
            
            <ul class="nav d-none d-lg-flex ms-4">
                <li class="nav-item"><a class="nav-link text-white-50 px-3" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white-50 px-3" href="services.php">Services</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3 ms-auto">
                <div class="d-none d-md-flex align-items-center bg-dark rounded-pill px-3 py-1 border border-secondary shadow-sm">
                    <i class="bi bi-coin text-warning me-2"></i>
                    <span class="text-light fw-bold small"><?php echo number_format($total_points); ?> Pts</span>
                </div>

                <div class="dropdown ms-3">
                    <a href="#" class="text-light text-decoration-none d-block" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5 text-primary"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-3 rounded-3">
                        <li><h6 class="dropdown-header text-primary fw-bold"><?php echo htmlspecialchars($user['name']); ?></h6></li>
                        <li><a class="dropdown-item py-2 active" href="profile.php"><i class="bi bi-gear me-2 text-secondary"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="../backend/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <main class="container py-5 flex-grow-1">
        <div class="row g-4">
            
            <aside class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="text-center mb-4 border-bottom pb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3 shadow-sm" style="width: 80px; height: 80px; font-size: 1.8rem;"><?php echo htmlspecialchars($initials); ?></div>
                        <h1 class="h5 fw-bold text-dark mb-1"><?php echo htmlspecialchars($user['name']); ?></h1>
                        <p class="text-secondary small mb-2"><?php echo htmlspecialchars($user['email']); ?></p>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                            <i class="bi bi-shield-check me-1"></i> Verified <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                        </span>
                    </div>
                </div>
            </aside>

            <section class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <h2 class="h4 fw-bold text-dark mb-4">Profile Configuration</h2>

                    <?php if ($success_msg): ?>
                        <div class="alert alert-success border-0 shadow-sm rounded-3 py-3 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-5 me-3"></i>
                            <div><?php echo htmlspecialchars($success_msg); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_msg): ?>
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>
                            <div><?php echo htmlspecialchars($error_msg); ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="../backend/profile_handler.php" class="needs-validation" novalidate>
                        
                        <h3 class="h6 fw-bold text-dark mb-3">Personal & Contact Data</h3>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control form-control-lg bg-light border-0" required value="<?php echo htmlspecialchars($user['name']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg bg-light border-0" required value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                        </div>

                        <h3 class="h6 fw-bold text-dark mb-3 mt-4 border-top pt-4">Geographic Anchor</h3>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">City</label>
                                <input type="text" name="city" class="form-control form-control-lg bg-light border-0" required value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Street Name</label>
                                <input type="text" name="street" class="form-control form-control-lg bg-light border-0" required value="<?php echo htmlspecialchars($user['street_name'] ?? ''); ?>">
                            </div>
                        </div>

                        <h3 class="h6 fw-bold text-dark mb-3 mt-4 border-top pt-4">Security Update (Optional)</h3>
                        <p class="text-secondary small mb-3">Leave blank to retain your current password.</p>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <input type="password" name="password" class="form-control form-control-lg bg-light border-0" placeholder="New password">
                            </div>
                            <div class="col-md-6">
                                <input type="password" name="confirm_password" class="form-control form-control-lg bg-light border-0" placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold px-5 shadow-sm">Commit Changes</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer-pro py-4 text-center mt-auto">
        <div class="container">
            <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> HomeFix Technician Booking Platform.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>