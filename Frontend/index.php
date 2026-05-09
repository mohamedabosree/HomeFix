<?php
// Initialize session and authentication logic
require 'backend/auth.php';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HomeFix connects you with background-checked plumbers, painters, electricians, and carpenters for fast, reliable home maintenance.">
    <meta name="theme-color" content="#0f172a">
    
    <meta property="og:title" content="HomeFix | Professional Technician Booking Platform">
    <meta property="og:description" content="Book certified plumbers, electricians, and carpenters with upfront pricing and a 30-day service warranty.">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="HomeFix">
    
    <title>HomeFix | Professional Technician Booking Platform</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body id="page">
    
    <div id="side-nav" class="position-fixed top-50 translate-middle-y" style="left: 0; z-index: 1040;">
        <div class="d-flex flex-column gap-3 p-2 bg-dark rounded-end shadow" style="transition: width 0.3s ease;">
            <a href="https://www.facebook.com/share/1HPQ5SuQhS/?mibextid=wwXIfr" target="_blank" class="text-white fs-4 hover-lift-icon" title="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="https://www.instagram.com/homefix___?igsh=Z3drajVxcXVqZGJx&utm_source=qr" target="_blank" class="text-white fs-4 hover-lift-icon" title="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="https://www.tiktok.com/@home.fix71?_r=1&_t=ZS-963hz867Nv2" target="_blank" class="text-white fs-4 hover-lift-icon" title="TikTok"><i class="bi bi-tiktok"></i></a>
            <a href="https://wa.me/20101234567" target="_blank" class="text-white fs-4 hover-lift-icon" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
            <a href="#contact" class="text-white fs-4 hover-lift-icon" title="Contact Us"><i class="bi bi-envelope"></i></a>
        </div>
    </div>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark navbar-pro shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold me-4" href="index.php" aria-label="HomeFix Home">HomeFix</a>
                
                <form class="d-none d-lg-flex flex-grow-1 mx-4" role="search" style="max-width: 400px;">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-secondary border-end-0"><i class="bi bi-search" aria-hidden="true"></i></span>
                        <input type="search" class="form-control bg-dark border-secondary text-light shadow-none border-start-0 ps-0" placeholder="Search for a service..." aria-label="Search">
                    </div>
                </form>

                <div class="d-flex align-items-center gap-3 gap-md-4 ms-auto">
                    <div class="d-none d-md-flex align-items-center bg-dark rounded-pill px-3 py-1 border border-secondary hover-lift-icon shadow-sm" title="HomeFix Rewards" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#rewardsModal">
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
                                        <li><a class="dropdown-item py-2" href="admin/index.php"><i class="bi bi-speedometer2 me-2 text-secondary" aria-hidden="true"></i> Admin Dashboard</a></li>
                                    <?php else: ?>
                                        <li><a class="dropdown-item py-2" href="account.php"><i class="bi bi-person-badge me-2 text-secondary" aria-hidden="true"></i> My Account</a></li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2 text-danger" href="backend/logout.php"><i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i> Logout</a></li>
                                <?php else: ?>
                                    <li><h6 class="dropdown-header text-primary fw-bold">Welcome, Guest</h6></li>
                                    <li><a class="dropdown-item py-2" href="auth.php"><i class="bi bi-box-arrow-in-right me-2 text-secondary" aria-hidden="true"></i> Login / Sign Up</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <button class="navbar-toggler border-0 p-0 ms-2 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </nav>
    </header>

    <div class="offcanvas offcanvas-end bg-dark text-light" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title fw-bold" id="offcanvasNavbarLabel">HomeFix Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#how-it-works">Process</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <?php if (isLoggedIn() && isAdmin()): ?>
                    <li class="nav-item"><a class="nav-link text-warning mt-3" href="admin/index.php">Admin Panel</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

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

    <main>
        <section id="home" class="hero-section text-center">
            <div class="container hero-content">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-4 shadow-sm fw-bold">Professional Maintenance Network</span>
                        <h1 class="display-4 fw-bold mb-4 lh-sm">Expert Technicians,<br>Guaranteed Results.</h1>
                        <p class="lead mb-5 text-white-50 mx-auto" style="max-width: 600px;">Book certified plumbers, electricians, and carpenters with upfront pricing and a 30-day service warranty.</p>
                        <div class="d-flex justify-content-center flex-wrap gap-3">
                            <a href="#services" class="btn btn-pro-primary btn-lg" onmouseover="hoverButton(this)" onmouseout="normalButton(this)">Explore Services</a>
                            <button type="button" class="btn btn-outline-light btn-lg" data-bs-toggle="modal" data-bs-target="#termsModal">
                                View Service Terms
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="container overlap-grid mb-5">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <a href="#services" class="text-decoration-none d-block h-100">
                        <div class="card h-100 border-0 shadow-lg text-center p-4 p-xl-5 hover-lift rounded-4">
                            <div class="icon-neumorphic mx-auto">
                                <i class="bi bi-search" aria-hidden="true"></i>
                            </div>
                            <h3 class="h5 fw-bold mt-2 text-dark">1. Select Service</h3>
                            <p class="text-secondary small mb-0">Choose from our list of vetted professional services.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="book.php" class="text-decoration-none d-block h-100">
                        <div class="card h-100 border-0 shadow-lg text-center p-4 p-xl-5 hover-lift rounded-4">
                            <div class="icon-neumorphic mx-auto">
                                <i class="bi bi-calendar-check" aria-hidden="true"></i>
                            </div>
                            <h3 class="h5 fw-bold mt-2 text-dark">2. Book Instantly</h3>
                            <p class="text-secondary small mb-0">Set your preferred date and describe your issue.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="success.php" class="text-decoration-none d-block h-100">
                        <div class="card h-100 border-0 shadow-lg text-center p-4 p-xl-5 hover-lift rounded-4">
                            <div class="icon-neumorphic mx-auto">
                                <i class="bi bi-shield-check" aria-hidden="true"></i>
                            </div>
                            <h3 class="h5 fw-bold mt-2 text-dark">3. Job Completed</h3>
                            <p class="text-secondary small mb-0">Learn about our 30-day warranty, reviews, and reward points.</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section id="services" class="py-5 bg-light">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bold text-dark">Our Core Services</h2>
                    <p class="text-secondary">From emergency repairs to routine maintenance, we have you covered.</p>
                </div>
                
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    
                    <article class="col">
                        <div class="card h-100 border-0 shadow-sm p-4 hover-lift rounded-4" onclick="changeBorder(this)">
                            <div class="icon-neumorphic"><i class="bi bi-droplet-fill" aria-hidden="true"></i></div>
                            <h3 class="h5 fw-bold text-dark mb-3">Plumbing</h3>
                            <p class="text-secondary small mb-4 flex-grow-1">Emergency leak detection, pipe installations, and water heater maintenance.</p>
                            <div class="d-flex flex-column gap-2 mt-auto">
                                <a href="book.php" class="btn btn-primary w-100">View Details</a>
                                <button type="button" class="btn btn-pro-outline w-100" onclick="window.addToCart('Plumbing Repair', 150)">Add to Cart (150 EGP)</button>
                            </div>
                        </div>
                    </article>

                    <article class="col">
                        <div class="card h-100 border-0 shadow-sm p-4 hover-lift rounded-4" onclick="changeBorder(this)">
                            <div class="icon-neumorphic"><i class="bi bi-palette-fill" aria-hidden="true"></i></div>
                            <h3 class="h5 fw-bold text-dark mb-3">Painting</h3>
                            <p class="text-secondary small mb-4 flex-grow-1">Flawless interior/exterior painting, wall treatments, and protective finishes.</p>
                            <div class="d-flex flex-column gap-2 mt-auto">
                                <a href="book.php" class="btn btn-primary w-100">View Details</a>
                                <button class="btn btn-pro-outline w-100" onclick="window.addToCart('Painting Service', 300)">Add to Cart (300 EGP)</button>
                            </div>
                        </div>
                    </article>

                    <article class="col">
                        <div class="card h-100 border-0 shadow-sm p-4 hover-lift rounded-4" onclick="changeBorder(this)">
                            <div class="icon-neumorphic"><i class="bi bi-hammer" aria-hidden="true"></i></div>
                            <h3 class="h5 fw-bold text-dark mb-3">Carpentry</h3>
                            <p class="text-secondary small mb-4 flex-grow-1">Custom woodwork, furniture assembly, and secure door/window installations.</p>
                            <div class="d-flex flex-column gap-2 mt-auto">
                                <a href="book.php" class="btn btn-primary w-100">View Details</a>
                                <button class="btn btn-pro-outline w-100" onclick="window.addToCart('Carpentry Assembly', 200)">Add to Cart (200 EGP)</button>
                            </div>
                        </div>
                    </article>

                    <article class="col">
                        <div class="card h-100 border-0 shadow-sm p-4 hover-lift rounded-4" onclick="changeBorder(this)">
                            <div class="icon-neumorphic"><i class="bi bi-lightning-charge-fill" aria-hidden="true"></i></div>
                            <h3 class="h5 fw-bold text-dark mb-3">Electrical</h3>
                            <p class="text-secondary small mb-4 flex-grow-1">Safe wiring, panel upgrades, and emergency short circuit restoration.</p>
                            <div class="d-flex flex-column gap-2 mt-auto">
                                <a href="book.php" class="btn btn-primary w-100">View Details</a>
                                <button class="btn btn-pro-outline w-100" onclick="window.addToCart('Electrical Repair', 250)">Add to Cart (250 EGP)</button>
                            </div>
                        </div>
                    </article>

                    <article class="col">
                        <div class="card h-100 border-0 shadow-sm p-4 hover-lift rounded-4" onclick="changeBorder(this)">
                            <div class="icon-neumorphic"><i class="bi bi-building" aria-hidden="true"></i></div>
                            <h3 class="h5 fw-bold text-dark mb-3">Formwork</h3>
                            <p class="text-secondary small mb-4 flex-grow-1">Structural concrete preparation, foundation shuttering, and metal framing.</p>
                            <div class="d-flex flex-column gap-2 mt-auto">
                                <a href="book.php" class="btn btn-primary w-100">View Details</a>
                                <button class="btn btn-pro-outline w-100" onclick="window.addToCart('Formwork Preparation', 500)">Add to Cart (500 EGP)</button>
                            </div>
                        </div>
                    </article>

                    <article class="col">
                        <div class="card h-100 border-0 shadow-sm p-4 hover-lift rounded-4" onclick="changeBorder(this)">
                            <div class="icon-neumorphic"><i class="bi bi-stars" aria-hidden="true"></i></div>
                            <h3 class="h5 fw-bold text-dark mb-3">Cleaning</h3>
                            <p class="text-secondary small mb-4 flex-grow-1">Deep residential sanitization, upholstery care, and post-construction cleanup.</p>
                            <div class="d-flex flex-column gap-2 mt-auto">
                                <a href="book.php" class="btn btn-primary w-100">View Details</a>
                                <button class="btn btn-pro-outline w-100" onclick="window.addToCart('Deep Cleaning', 400)">Add to Cart (400 EGP)</button>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="reviews" class="py-5 bg-white border-top">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bold text-dark">Trusted by Homeowners</h2>
                    <p class="text-secondary">Real reviews from verified HomeFix customers across Cairo.</p>
                </div>
                
                <div id="customerReviewsCarousel" class="carousel slide" data-bs-ride="carousel" style="max-width: 800px; margin: 0 auto;">
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-bs-interval="4000">
                            <div class="card border-0 shadow-sm p-4 text-center rounded-4 mx-3">
                                <div class="text-warning mb-3"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                                <p class="text-secondary fs-5 fst-italic">"The plumber arrived within 45 minutes to fix a massive pipe leak in my kitchen. Completely professional."</p>
                                <h4 class="h6 fw-bold text-dark mt-3">- Ahmed M., Heliopolis</h4>
                            </div>
                        </div>
                        <div class="carousel-item" data-bs-interval="4000">
                            <div class="card border-0 shadow-sm p-4 text-center rounded-4 mx-3">
                                <div class="text-warning mb-3"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                                <p class="text-secondary fs-5 fst-italic">"I booked an electrician for a panel upgrade. He was licensed and finished ahead of schedule. Highly recommended."</p>
                                <h4 class="h6 fw-bold text-dark mt-3">- Sarah N., Maadi</h4>
                            </div>
                        </div>
                        <div class="carousel-item" data-bs-interval="4000">
                            <div class="card border-0 shadow-sm p-4 text-center rounded-4 mx-3">
                                <div class="text-warning mb-3"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                                <p class="text-secondary fs-5 fst-italic">"Used HomeFix to deep clean my new apartment post-construction. The crew was meticulous. Worth the investment."</p>
                                <h4 class="h6 fw-bold text-dark mt-3">- Omar K., New Cairo</h4>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide="prev">
                        <i class="bi bi-chevron-left text-dark fs-2" aria-hidden="true"></i>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide="next">
                        <i class="bi bi-chevron-right text-dark fs-2" aria-hidden="true"></i>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>

        <section id="contact" class="py-5 bg-light border-top">
            <div class="container py-4 text-center">
                <h2 class="h3 fw-bold text-dark mb-5">Need Immediate Assistance?</h2>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="bi bi-telephone-fill text-primary fs-2 mb-2" aria-hidden="true"></i>
                            <h4 class="h6 fw-bold text-dark">Call Us</h4>
                            <a href="tel:+20101234567" class="text-secondary text-decoration-none">+20 (101) 123-4567</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer-pro py-5 text-center mt-auto">
        <div class="container">
            <div class="navbar-brand fw-bold mb-3 fs-3 text-white">HomeFix</div>
            <p class="mb-0 small text-white-50">&copy; <span id="copyright-year"></span> HomeFix Technician Booking Platform.</p>
            <script>
                document.getElementById('copyright-year').textContent = new Date().getFullYear();
            </script>
        </div>
    </footer>

    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="termsModalLabel">Service Terms</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Standard service terms apply. All bookings include a 30-day warranty.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rewardsModal" tabindex="-1" aria-labelledby="rewardsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-3">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h2 class="h4 fw-bold text-dark mb-3">HomeFix Rewards</h2>
                    <p class="text-secondary small mb-4">Subscribe to receive your points statements and offers.</p>
                    <form onsubmit="window.showMessage(event)">
                        <input type="text" class="form-control mb-3" placeholder="First Name" required>
                        <input type="email" class="form-control mb-3" placeholder="Email Address" required>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>