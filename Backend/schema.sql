-- HOMEFIX DATABASE SCHEMA
-- Execute this entire file in phpMyAdmin to initialize the database

CREATE DATABASE IF NOT EXISTS homefix_db;
USE homefix_db;

-- ==========================================
-- 1. USERS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 2. SERVICES TABLE
-- ==========================================
-- Replaces the generic 'products' table from the demo
CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_icon VARCHAR(255) DEFAULT 'bi-tools',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 3. BOOKINGS TABLE
-- ==========================================
-- Replaces the 'orders' table. Captures comprehensive transaction data.
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    booking_date DATE NOT NULL,
    problem_description TEXT NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- ==========================================
-- 4. INITIALIZATION DATA
-- ==========================================

-- Insert Default Accounts (Passwords are plain text per project scope constraints)
INSERT INTO users (name, email, password, role) VALUES
('HomeFix Administrator', 'admin@homefix.com', 'admin123', 'admin'),
('Mohamed Abosree', 'mohamed@test.com', 'user123', 'user');

-- Insert Core Service Catalog
INSERT INTO services (name, description, price, image_icon) VALUES
('Plumbing Repair', 'Emergency leak detection, pipe installations, and water heater maintenance.', 150.00, 'bi-droplet-fill'),
('Electrical Repair', 'Safe wiring, panel upgrades, and emergency short circuit restoration.', 250.00, 'bi-lightning-charge-fill'),
('Carpentry Assembly', 'Custom woodwork, furniture assembly, and secure door/window installations.', 200.00, 'bi-hammer'),
('Painting Service', 'Flawless interior/exterior painting, wall treatments, and protective finishes.', 300.00, 'bi-palette-fill'),
('Deep Cleaning', 'Deep residential sanitization, upholstery care, and post-construction cleanup.', 400.00, 'bi-stars'),
('Formwork Preparation', 'Structural concrete preparation, foundation shuttering, and metal framing.', 500.00, 'bi-building');