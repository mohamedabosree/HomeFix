<?php

require_once '../backend/auth.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: ../admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$login_error = $_SESSION['login_error'] ?? '';
$register_error = $_SESSION['register_error'] ?? '';
unset($_SESSION['login_error'], $_SESSION['register_error']);

$active_tab = !empty($register_error) ? 'register' : 'login';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Access | HomeFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--corporate-navy) 0%, #1e293b 100%);
            min-height: 100vh;
        }
        .auth-card {
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
        }
        [data-bs-theme="dark"] .auth-card {
            background: rgba(30, 41, 59, 0.98);
        }
        .nav-tabs .nav-link {
            border: none;
            color: var(--text-main);
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link.active {
            background: transparent;
            color: var(--accent-blue) !important;
            border-bottom-color: var(--accent-blue);
            font-weight: 700;
        }
        .input-group-text {
            background-color: var(--pro-bg-light);
            border: 1px solid var(--border-soft);
            color: var(--accent-blue);
        }
    </style>
</head>
<body class="d-flex align-items-center py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card auth-card shadow-lg overflow-hidden border-0">
                    <div class="row g-0">
                        <div class="col-lg-5 d-none d-lg-block bg-primary p-5 text-white position-relative overflow-hidden">
                            <div class="position-relative z-3">
                                <a href="index.php" class="text-white text-decoration-none">
                                    <h2 class="fw-bold mb-4">HomeFix</h2>
                                </a>
                                <h3 class="display-6 fw-bold mb-4">Certified Local Expertise.</h3>
                                <p class="lead opacity-75">Join thousands of Cairo homeowners who trust our verified technician network for 30-day guaranteed results.</p>
                                
                                <ul class="list-unstyled mt-5">
                                    <li class="mb-3"><i class="bi bi-shield-check-fill me-2"></i> Background-Checked Pros</li>
                                    <li class="mb-3"><i class="bi bi-cash-stack me-2"></i> Upfront Pricing</li>
                                    <li class="mb-3"><i class="bi bi-clock-history me-2"></i> Emergency Dispatch</li>
                                </ul>
                            </div>
                            <div class="position-absolute bottom-0 end-0 p-4 opacity-25">
                                <i class="bi bi-house-gear-fill display-1"></i>
                            </div>
                        </div>

                        <div class="col-lg-7 p-4 p-md-5">
                            <div class="d-lg-none text-center mb-4">
                                <h2 class="fw-bold text-primary">HomeFix</h2>
                            </div>

                            <ul class="nav nav-tabs nav-fill mb-5" id="authTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link py-3 <?php echo $active_tab === 'login' ? 'active' : ''; ?>" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button">Secure Login</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link py-3 <?php echo $active_tab === 'register' ? 'active' : ''; ?>" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button">Create Account</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="authTabsContent">
                                <div class="tab-pane fade <?php echo $active_tab === 'login' ? 'show active' : ''; ?>" id="login">
                                    <?php if ($login_error): ?>
                                        <div class="alert alert-danger border-0 shadow-sm py-2 mb-4"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($login_error); ?></div>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="../backend/login_handler.php" class="needs-validation" novalidate>
                                        <div class="mb-4">
                                            <label class="form-label small fw-bold text-secondary">Email Address</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text border-end-0"><i class="bi bi-envelope"></i></span>
                                                <input type="email" name="email" class="form-control border-start-0" placeholder="name@example.com" required>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label small fw-bold text-secondary">Password</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text border-end-0"><i class="bi bi-lock"></i></span>
                                                <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm py-3 mb-3">Authenticate Account</button>
                                        <p class="text-center text-secondary small">Forgot your password? <a href="#" class="text-primary text-decoration-none fw-bold">Reset Here</a></p>
                                    </form>
                                </div>

                                <div class="tab-pane fade <?php echo $active_tab === 'register' ? 'show active' : ''; ?>" id="register">
                                    <?php if ($register_error): ?>
                                        <div class="alert alert-danger border-0 shadow-sm py-2 mb-4"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($register_error); ?></div>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="../backend/register_handler.php" class="needs-validation" novalidate>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-12">
                                                <label class="form-label small fw-bold text-secondary">Full Identity Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input type="text" name="name" class="form-control" placeholder="Mohamed Abo Sree" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label small fw-bold text-secondary">Email Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                                    <input type="email" name="email" class="form-control" placeholder="mohamed@example.com" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-secondary">City</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                    <input type="text" name="city" class="form-control" placeholder="Giza" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-secondary">Street Name</label>
                                                <input type="text" name="street" class="form-control" placeholder="El-Bahr El-Aazam" required>
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-secondary">Password</label>
                                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-secondary">Confirm</label>
                                                <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm py-3">Initialize Account</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="index.php" class="text-white-50 text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Return to Homepage</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>
