<?php


require_once '../backend/auth.php';

if (!isLoggedIn()) {
    header("Location: auth.php");
    exit;
}

$booking_id = $_SESSION['last_booking_id'] ?? null;

if ($booking_id) {
    unset($_SESSION['last_booking_id']);
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed | HomeFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <header class="navbar-pro shadow-sm sticky-top">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <a class="navbar-brand fw-bold fs-3 text-white text-decoration-none" href="index.php">HomeFix</a>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <a href="profile.php" class="text-light text-decoration-none"><i class="bi bi-person-circle fs-5 me-2"></i><?php echo htmlspecialchars($_SESSION['name']); ?></a>
            </div>
        </div>
    </header>

    <main class="container py-5 flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center p-4 p-md-5">
                    
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h1 class="h3 fw-bold text-dark mb-3">Booking Confirmed!</h1>
                    
                    <?php if ($booking_id): ?>
                        <div class="bg-light border rounded-3 p-3 mb-4 d-inline-block">
                            <span class="text-secondary small fw-bold text-uppercase d-block mb-1">Transaction Reference</span>
                            <span class="fs-5 fw-bold text-dark">#HF-<?php echo str_pad($booking_id, 6, "0", STR_PAD_LEFT); ?></span>
                        </div>
                    <?php endif; ?>

                    <p class="text-secondary mb-5">
                        Your service request has been successfully injected into our dispatch system. A certified HomeFix technician will contact you at your provided phone number shortly to confirm the exact arrival window.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="index.php" class="btn btn-primary btn-lg fw-bold px-4">Return Home</a>
                        <a href="services.php" class="btn btn-outline-secondary btn-lg fw-bold px-4">Browse Services</a>
                    </div>

                </div>

            </div>
        </div>
    </main>

    <footer class="footer-pro py-4 text-center mt-auto bg-dark">
        <div class="container">
            <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> HomeFix Technician Booking Platform.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
