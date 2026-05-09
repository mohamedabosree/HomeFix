<?php
/* FRONTEND PROFILE & SETTINGS
 * Displays the user dashboard and profile update form.
 */

require_once '../backend/auth.php';
require_once '../backend/user_db.php';

// Strictly enforce authentication
if (!isLoggedIn()) {
    header("Location: ../auth.php");
    exit;
}

// Retrieve the active user's complete data payload
$user = getUserById($_SESSION['user_id']);

// Failsafe: Terminate session if user data cannot be retrieved (e.g., account deleted)
if (!$user) {
    logoutUser();
    header("Location: ../auth.php");
    exit;
}

// Extract session alerts
$success_msg = $_SESSION['profile_success'] ?? '';
$error_msg = $_SESSION['profile_error'] ?? '';
unset($_SESSION['profile_success'], $_SESSION['profile_error']);

// Calculate dynamic avatar initials
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
    <meta name="description" content="Manage your HomeFix account settings and profile information.">
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
                    <span class="text-light fw-bold small">450 Pts</span>
                </div>

                <div class="d-none d-md-block vr text-white-50 opacity-25" style="width: 2px; min-height: 24px;"></div>

                <div class="dropdown">
                    <a href="#" class="text-light text-decoration-none d-block ms-2" data-bs-toggle="dropdown">
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
                    <nav class="nav flex-column gap-2">
                        <a class="nav-link text-secondary px-3 py-2" href="../index.php"><i class="bi bi-house me-2"></i> Return Home</a>
                        <a class="nav-link active fw-bold text-primary bg-primary bg-opacity-10 rounded-3 px-3 py-2" href="profile.php"><i class="bi bi-gear me-2"></i> Account Settings</a>
                    </nav>
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

                        <hr class="border-secondary opacity-25 my-4">
                        
                        <h3 class="h6 fw-bold text-dark mb-3">Security Update (Optional)</h3>
                        <p class="text-secondary small mb-4">Leave these fields blank if you do not wish to change your current password.</p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">New Password</label>
                                <input type="password" name="password" class="form-control form-control-lg bg-light border-0" placeholder="Enter new password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control form-control-lg bg-light border-0" placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold px-5 shadow-sm">Save Changes</button>
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