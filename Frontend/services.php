<?php
/* FRONTEND SERVICES CATALOG
 * Displays all available HomeFix services dynamically from the database.
 */

// Initialize session and database functions
require '../backend/auth.php';
require '../backend/services_db.php';

if (!function_exists('getAllServices')) {
    function getAllServices() {
        return [];
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return false;
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return false;
    }
}

// Fetch the catalog from the database
$services = getAllServices();
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
    <script src="script.js" defer></script>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

    <header class="navbar-pro shadow-sm sticky-top">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <a class="navbar-brand fw-bold fs-3 text-white text-decoration-none" href="index.php" aria-label="HomeFix Home">HomeFix</a>
            
            <ul class="nav d-none d-lg-flex ms-4">
                <li class="nav-item"><a class="nav-link text-white-50 px-3" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold px-3" href="services.php">Services</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3 gap-md-4 ms-auto">
                <div class="d-none d-md-flex align-items-center bg-dark rounded-pill px-3 py-1 border border-secondary hover-lift-icon shadow-sm">
                    <i class="bi bi-coin text-warning me-2" aria-hidden="true"></i>
                    <span class="text-light fw-bold small">0 Pts</span>
                </div>

                <div class="d-none d-md-block vr text-white-50 opacity-25" style="width: 2px; min-height: 24px;"></div>

                <div class="d-flex align-items-center gap-3">
                    <button id="theme-toggle" aria-label="Toggle Dark Mode" class="bg-transparent border-0 p-0 text-light hover-lift-icon d-flex align-items-center" onclick="window.toggleDarkMode()">
                        <i class="bi bi-moon-stars-fill fs-5" id="theme-icon" aria-hidden="true"></i>
                    </button>

                    <button class="btn btn-outline-warning border-0 position-relative p-1" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-label="View Cart">
                        <i class="bi bi-cart3 fs-5" aria-hidden="true"></i>
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                    </button>

                    <div class="dropdown">
                        <a href="#" class="text-light text-decoration-none d-block ms-2" id="userAccountMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User Account Menu">
                            <i class="bi bi-person-circle fs-5 hover-lift-icon" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-3 rounded-3" aria-labelledby="userAccountMenu">
                            <?php if (isLoggedIn()): ?>
                                <li><h6 class="dropdown-header text-primary fw-bold">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h6></li>
                                <?php if (isAdmin()): ?>
                                    <li><a class="dropdown-item py-2" href="../admin/index.php"><i class="bi bi-speedometer2 me-2 text-secondary" aria-hidden="true"></i> Admin Dashboard</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person-badge me-2 text-secondary" aria-hidden="true"></i> My Account</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="../backend/logout.php"><i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i> Logout</a></li>
                            <?php else: ?>
                                <li><h6 class="dropdown-header text-primary fw-bold">Welcome, Guest</h6></li>
                                <li><a class="dropdown-item py-2" href="login.php"><i class="bi bi-box-arrow-in-right me-2 text-secondary" aria-hidden="true"></i> Login / Sign Up</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="cartOffcanvasLabel"><i class="bi bi-cart3 me-2"></i>Service Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div id="cart-items" class="flex-grow-1 text-center mt-5 text-secondary">
                <i class="bi bi-basket2 display-1 opacity-25"></i>
                <p class="mt-3">Your service cart is empty.</p>
            </div>
            <div class="border-top pt-3 mt-auto">
                <div class="d-flex justify-content-between fw-bold mb-3">
                    <span>Total Estimate:</span>
                    <span id="cart-total">0 EGP</span>
                </div>
                <a href="book.php" class="btn btn-primary w-100 fw-bold">Proceed to Checkout</a>
            </div>
        </div>
    </div>

    <main class="flex-grow-1 py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h1 class="display-6 fw-bold text-dark">Our Core Services</h1>
                <p class="text-secondary">From emergency repairs to routine maintenance, we have you covered.</p>
            </div>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php if (empty($services)): ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-tools display-1 text-secondary opacity-25"></i>
                        <p class="mt-3 text-secondary">No services are currently available in the catalog.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($services as $service): ?>
                        <article class="col">
                            <div class="card h-100 border-0 shadow-sm p-4 hover-lift rounded-4" onclick="window.changeBorder(this)">
                                <div class="icon-neumorphic">
                                    <i class="bi <?php echo htmlspecialchars($service['image_icon'] ?? 'bi-tools'); ?>" aria-hidden="true"></i>
                                </div>
                                
                                <h3 class="h5 fw-bold text-dark mb-3"><?php echo htmlspecialchars($service['name']); ?></h3>
                                <p class="text-secondary small mb-4 flex-grow-1"><?php echo htmlspecialchars($service['description']); ?></p>
                                
                                <div class="d-flex flex-column gap-2 mt-auto">
                                    <a href="book.php" class="btn btn-primary w-100">Book Now</a>
                                    
                                    <button type="button" class="btn btn-pro-outline w-100" 
                                            onclick="window.addToCart('<?php echo addslashes($service['name']); ?>', <?php echo (float)$service['price']; ?>)">
                                        Add to Cart (<?php echo number_format($service['price'], 2); ?> EGP)
                                    </button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="footer-pro py-5 text-center mt-auto">
        <div class="container">
            <div class="navbar-brand fw-bold mb-3 fs-3 text-white">HomeFix</div>
            <p class="mb-0 small text-white-50">&copy; <?php echo date('Y'); ?> HomeFix Technician Booking Platform.</p>            
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>