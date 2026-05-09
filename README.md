HOMEFIX_APP/
│
├── index.php                 # Root entry point (redirects to frontend/index.php)
│
├── backend/                  # PHP logic & database functions
│   ├── db.php                # Database connection setup
│   ├── schema.sql            # Database tables for users, services, & bookings
│   ├── auth.php              # Core authentication logic (Login/Register/Logout)
│   ├── services_db.php       # Specialized queries for technician services
│   ├── user_db.php           # Functions to manage user profile data
│   ├── admin_db.php          # Full CRUD logic for the admin dashboard
│   ├── login_handler.php     # Processes user login attempts
│   ├── register_handler.php  # Processes new account sign-ups
│   └── profile_handler.php   # Handles user information update requests
│
├── frontend/                 # Public-facing user pages
│   ├── index.php             # Homepage with service highlights & hero section
│   ├── login.php             # User login portal
│   ├── register.php          # User registration portal
│   ├── services.php          # Listing of all available maintenance categories
│   ├── profile.php           # User account dashboard & personalized settings
│   ├── logout.php            # Session termination handler
│   ├── style.css             # Unified stylesheet for the entire platform
│   └── script.js             # Logic for Dark Mode, Cart, and interactions
│
└── admin/                    # Secure administrative management
    ├── index.php             # Admin dashboard with summary statistics
    ├── manage_services.php   # Interface for adding/editing service items
    ├── manage_users.php      # Oversight of customer & technician accounts
    └── manage_bookings.php   # Tracking and updating service booking statuses
