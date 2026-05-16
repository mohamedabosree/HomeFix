<?php
/* FRONTEND SERVICES CATALOG
 * Displays available HomeFix services dynamically, grouped by taxonomic category.
 */

require_once '../backend/auth.php';
require_once '../backend/services_db.php';

// Fetch the catalog integrated with category taxonomy
$services = getAllServices();

// Restructure the flattened SQL array into a multidimensional array grouped by category
$categorized_services = [];
foreach ($services as $service) {
    $category = $service['category_name'] ?? 'Uncategorized Services';
    $categorized_services[$category][] = $service;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse our comprehensive catalog of professional home maintenance services.">
    <title>Our Services | HomeFix</title>
    
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
                <li class="nav-item"><a class="nav-link text-white px-3 fw-bold" href="services.php">Services</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <button class="btn btn-link text-light p-0 position-relative me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                    <i class="bi bi-cart3 fs-5"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">0</span>
                </button>

                <?php if (isLoggedIn()): ?>
                    <a href="profile.php" class="text-light text-decoration-none"><i class="bi bi-person-circle fs-5 me-2"></i>Profile</a>
                <?php else: ?>
                    <a href="auth.php" class="btn btn-primary btn-sm fw-bold">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container py-5 flex-grow-1">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-dark">Professional Service Catalog</h1>
            <p class="text-secondary lead">Select a maintenance category below to view our standardized pricing.</p>
        </div>

        <?php if (empty($categorized_services)): ?>
            <div class="alert alert-info text-center shadow-sm border-0">The service catalog is currently undergoing maintenance. Check back shortly.</div>
        <?php else: ?>
            <?php foreach ($categorized_services as $category_name => $items): ?>
                
                <h2 class="h4 fw-bold text-primary mb-4 mt-5 border-bottom pb-2"><?php echo htmlspecialchars($category_name); ?></h2>
                
                <div class="row g-4 mb-5">
                    <?php foreach ($items as $service): ?>
                        <div class="col-md-6 col-lg-4">
                            <article class="card h-100 border-0 shadow-sm rounded-4 hover-lift">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                            <i class="bi <?php echo htmlspecialchars($service['image_icon']); ?> fs-3"></i>
                                        </div>
                                        <span class="fs-5 fw-bold text-dark"><?php echo number_format($service['price'], 2); ?> EGP</span>
                                    </div>
                                    <h3 class="h5 fw-bold text-dark mb-2"><?php echo htmlspecialchars($service['name']); ?></h3>
                                    <p class="text-secondary small mb-4 flex-grow-1"><?php echo htmlspecialchars($service['description']); ?></p>
                                    
                                    <div class="d-flex flex-column gap-2 mt-auto">
                                        <a href="book.php" class="btn btn-primary w-100 fw-bold">Schedule Service</a>
                                        <button type="button" class="btn btn-outline-secondary w-100 fw-bold" 
                                                onclick="window.addToCart('<?php echo addslashes($service['name']); ?>', <?php echo (float)$service['price']; ?>)">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <div class="offcanvas offcanvas-end shadow" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold"><i class="bi bi-cart3 me-2 text-primary"></i>Service Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div id="cart-items" class="flex-grow-1 overflow-auto">
                <div class="text-center text-secondary py-5">Your cart is currently empty.</div>
            </div>
            <div class="border-top pt-3 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-secondary">Estimated Total:</span>
                    <span id="cart-total" class="fs-4 fw-bold text-dark">0 EGP</span>
                </div>
                <a href="book.php" class="btn btn-primary w-100 btn-lg fw-bold shadow-sm">Proceed to Dispatch</a>
            </div>
        </div>
    </div>

    <footer class="footer-pro py-5 text-center mt-auto bg-dark">
        <div class="container">
            <div class="navbar-brand fw-bold mb-3 fs-3 text-white">HomeFix</div>
            <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> HomeFix Technician Booking Platform. All rights reserved.</p>            
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>