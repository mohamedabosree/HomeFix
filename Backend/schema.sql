-- HOMEFIX COMPREHENSIVE DATABASE SCHEMA
-- Target Database: homefix_db

CREATE DATABASE IF NOT EXISTS homefix_db;
USE homefix_db;

-- ==========================================
-- 1. LOCATIONS TABLE
-- Maps to ERD 'Locations'. Serves both Users and Technicians.
-- ==========================================
CREATE TABLE IF NOT EXISTS locations (
    location_id INT PRIMARY KEY AUTO_INCREMENT,
    city VARCHAR(100) NOT NULL,
    street_name VARCHAR(150) NOT NULL
);

-- ==========================================
-- 2. USERS TABLE (ERD: Customer)
-- Retains PHP naming convention ('users') to support authentication handlers.
-- ==========================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    location_id INT DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    picture VARCHAR(255) DEFAULT 'default.png', -- Integrated academic profile picture column
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (location_id) REFERENCES locations(location_id) ON DELETE SET NULL
);

-- ==========================================
-- 3. TECHNICIANS TABLE
-- Maps to ERD 'Technicians'.
-- ==========================================
CREATE TABLE IF NOT EXISTS technicians (
    tech_id INT PRIMARY KEY AUTO_INCREMENT,
    location_id INT DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    availability_status ENUM('available', 'busy', 'offline') DEFAULT 'available',
    FOREIGN KEY (location_id) REFERENCES locations(location_id) ON DELETE SET NULL
);

-- ==========================================
-- 4. CATEGORIES TABLE
-- Maps to ERD 'Categories'.
-- ==========================================
CREATE TABLE IF NOT EXISTS categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    description TEXT
);

-- ==========================================
-- 5. SERVICES TABLE (ERD: Service)
-- Retains PHP structure, adds ERD Category relation.
-- ==========================================
CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT DEFAULT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_icon VARCHAR(255) DEFAULT 'bi-tools',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

-- ==========================================
-- 6. BOOKINGS TABLE (ERD: Bookings)
-- Retains PHP structure, integrates ERD Technician assignment.
-- ==========================================
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    tech_id INT DEFAULT NULL,
    phone VARCHAR(20) NOT NULL,
    booking_date DATE NOT NULL,
    problem_description TEXT NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (tech_id) REFERENCES technicians(tech_id) ON DELETE SET NULL
);

-- ==========================================
-- 7. PAYMENTS TABLE
-- Maps to ERD 'Payments'.
-- ==========================================
CREATE TABLE IF NOT EXISTS payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('cash', 'credit_card', 'wallet') NOT NULL,
    status ENUM('pending', 'successful', 'failed') DEFAULT 'pending',
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- ==========================================
-- 8. REVIEWS TABLE
-- Maps to ERD 'Reviews'.
-- ==========================================
CREATE TABLE IF NOT EXISTS reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ==========================================
-- 9. LOYALTY POINTS TABLE
-- Maps to ERD 'LoyaltyPoints'.
-- ==========================================
CREATE TABLE IF NOT EXISTS loyalty_points (
    point_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    booking_id INT DEFAULT NULL,
    points_amount INT NOT NULL,
    date_earned TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL
);

-- ==========================================
-- 10. WARRANTY TABLE
-- Maps to ERD 'Warranty'.
-- ==========================================
CREATE TABLE IF NOT EXISTS warranties (
    warranty_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- ==========================================
-- 11. SYSTEM INITIALIZATION DATA
-- Ensures PHP application functions immediately upon deployment.
-- ==========================================

-- Default Administrator
INSERT INTO users (name, email, password, role) VALUES
('HomeFix Administrator', 'admin@homefix.com', 'admin123', 'admin');

-- Default Category for initial services
INSERT INTO categories (category_name, description) VALUES
('General Maintenance', 'Standard home repair and upkeep tasks.');

-- Default Service Catalog mapped to Category ID 1
INSERT INTO services (category_id, name, description, price, image_icon) VALUES
(1, 'Plumbing Repair', 'Emergency leak detection, pipe installations, and water heater maintenance.', 150.00, 'bi-droplet-fill'),
(1, 'Electrical Repair', 'Safe wiring, panel upgrades, and emergency short circuit restoration.', 250.00, 'bi-lightning-charge-fill'),
(1, 'Carpentry Assembly', 'Custom woodwork, furniture assembly, and secure door/window installations.', 200.00, 'bi-hammer'),
(1, 'Painting Service', 'Flawless interior/exterior painting, wall treatments, and protective finishes.', 300.00, 'bi-palette-fill'),
(1, 'Deep Cleaning', 'Deep residential sanitization, upholstery care, and post-construction cleanup.', 400.00, 'bi-stars'),
(1, 'Formwork Preparation', 'Structural concrete preparation, foundation shuttering, and metal framing.', 500.00, 'bi-building');