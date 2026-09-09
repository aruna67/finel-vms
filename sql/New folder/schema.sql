-- Army VMS Database Schema
-- MySQL 5.7+ / MariaDB

DROP SCHEMA IF EXISTS army_vms;
CREATE SCHEMA army_vms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE army_vms;

CREATE TABLE IF NOT EXISTS units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    code VARCHAR(30) NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT IGNORE INTO units (id,name,code) VALUES (1,'Default Unit','DEFAULT');

-- Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    rank VARCHAR(50),
    role ENUM('admin','officer','driver','mechanic','fuel_manager') NOT NULL DEFAULT 'officer',
    unit_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_users_unit (unit_id)
);

-- Vehicles
CREATE TABLE IF NOT EXISTS vehicles (
    id VARCHAR(20) PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    category ENUM('light','heavy','threeWheel','motorcycle','apc','utility') NOT NULL,
    model VARCHAR(100) NOT NULL,
    registration VARCHAR(30) NOT NULL UNIQUE,
    year YEAR NOT NULL,
    fuel_type ENUM('diesel','petrol') NOT NULL DEFAULT 'diesel',
    fuel_capacity INT NOT NULL DEFAULT 80,
    fuel_level INT NOT NULL DEFAULT 100,
    status ENUM('active','inactive','maintenance') NOT NULL DEFAULT 'active',
    unit VARCHAR(100),
    assigned_driver VARCHAR(20),
    work_ticket VARCHAR(50),
    work_ticket_status ENUM('active','pending','completed') DEFAULT 'active',
    in_out_status ENUM('idle','out','in') DEFAULT 'idle',
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    location_accuracy INT,
    location_source VARCHAR(20),
    location_timestamp DATETIME,
    is_geofence_violation TINYINT(1) NOT NULL DEFAULT 0,
    last_geofence_id INT NULL,
    image_path VARCHAR(500),
    vehicle_photo_path VARCHAR(500),
    next_maintenance_date DATE NULL,
    current_odometer INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    unit_id INT NULL,
    INDEX idx_vehicles_unit (unit_id)
);

-- Geofences and emergency alerts
CREATE TABLE IF NOT EXISTS geofences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('allowed','forbidden') NOT NULL DEFAULT 'forbidden',
    coordinates JSON NOT NULL,
    vehicle_id VARCHAR(20) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    unit_id INT NULL, INDEX idx_geofences_unit (unit_id)
);

-- Drivers
CREATE TABLE IF NOT EXISTS drivers (
    id VARCHAR(20) PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    rank VARCHAR(50),
    license_class VARCHAR(5),
    license_expiry DATE,
    phone VARCHAR(20),
    whatsapp VARCHAR(20),
    email VARCHAR(100),
    status ENUM('idle','on_duty','off','inactive') DEFAULT 'idle',
    assigned_vehicles TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unit_id INT NULL, INDEX idx_drivers_unit (unit_id)
);

CREATE TABLE IF NOT EXISTS emergency_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id VARCHAR(20) NULL,
    driver_id VARCHAR(20) NULL,
    alert_type VARCHAR(50) NOT NULL,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    message VARCHAR(500),
    status ENUM('open','acknowledged','resolved') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE SET NULL,
    unit_id INT NULL, INDEX idx_emergency_unit (unit_id)
);
-- Driver-Vehicle assignments (many-to-many)
CREATE TABLE IF NOT EXISTS driver_vehicles (
    driver_id VARCHAR(20),
    vehicle_id VARCHAR(20),
    PRIMARY KEY (driver_id, vehicle_id),
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    unit_id INT NULL, INDEX idx_driver_vehicles_unit (unit_id)
);

-- Fuel Stock
CREATE TABLE IF NOT EXISTS fuel_stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    diesel INT DEFAULT 0,
    petrol INT DEFAULT 0,
    diesel_threshold INT DEFAULT 2000,
    petrol_threshold INT DEFAULT 1000,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    unit_id INT NULL, INDEX idx_fuel_stock_unit (unit_id)
);

-- Fuel Transactions
CREATE TABLE IF NOT EXISTS fuel_transactions (
    id VARCHAR(20) PRIMARY KEY,
    type ENUM('allocation','refill') NOT NULL,
    date DATE NOT NULL,
    vehicle_id VARCHAR(20),
    fuel_type ENUM('diesel','petrol') NOT NULL,
    amount INT NOT NULL,
    purpose VARCHAR(255),
    authorized_by VARCHAR(100),
    authorized_officer_phone VARCHAR(20),
    stock_balance_diesel INT,
    stock_balance_petrol INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    unit_id INT NULL, INDEX idx_fuel_transactions_unit (unit_id)
);

-- Service Records
CREATE TABLE IF NOT EXISTS service_records (
    id VARCHAR(20) PRIMARY KEY,
    vehicle_id VARCHAR(20),
    date DATE NOT NULL,
    type ENUM('routine','repair','inspection') NOT NULL,
    description TEXT,
    cost DECIMAL(10,2),
    technician VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    unit_id INT NULL, INDEX idx_service_unit (unit_id)
);

-- Movements (Check-in / Check-out)
CREATE TABLE IF NOT EXISTS movements (
    id VARCHAR(20) PRIMARY KEY,
    vehicle_id VARCHAR(20),
    driver_id VARCHAR(20),
    check_out DATETIME,
    expected_return DATETIME,
    destination VARCHAR(255),
    purpose VARCHAR(255),
    authorized_by VARCHAR(100),
    status ENUM('out','completed','pending') DEFAULT 'out',
    check_in DATETIME,
    notes TEXT,
    return_notes TEXT,
    checked_in_by VARCHAR(100),
    checkout_fuel_level INT,
    fuel_level INT,
    checkout_odometer INT,
    checkin_odometer INT,
    condition_status VARCHAR(30),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE SET NULL,
    unit_id INT NULL, INDEX idx_movements_unit (unit_id)
);

-- GPS Devices
CREATE TABLE IF NOT EXISTS gps_devices (
    id VARCHAR(20) PRIMARY KEY,
    vehicle_id VARCHAR(20),
    type VARCHAR(30),
    update_interval INT DEFAULT 30,
    status ENUM('online','offline') DEFAULT 'online',
    last_update DATETIME,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    accuracy INT,
    battery_level INT,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    unit_id INT NULL, INDEX idx_gps_devices_unit (unit_id)
);

-- GPS Tracking History
CREATE TABLE IF NOT EXISTS gps_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id VARCHAR(20),
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    accuracy INT,
    source VARCHAR(20),
    timestamp DATETIME,
    speed_kmh DECIMAL(6,2),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    unit_id INT NULL, INDEX idx_gps_tracking_unit (unit_id)
);

CREATE TABLE IF NOT EXISTS speed_alerts (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id VARCHAR(20) NOT NULL,
    movement_id VARCHAR(20) NULL,
    speed_kmh DECIMAL(6,2) NOT NULL,
    officer_phone VARCHAR(20) NOT NULL,
    notified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unit_id INT NULL,
    INDEX idx_speed_alert_vehicle_time (vehicle_id, notified_at)
);

-- Bootstrap hash generated with password_hash('change-me-immediately', PASSWORD_DEFAULT).
INSERT IGNORE INTO users (username, password, full_name, rank, role)
VALUES ('admin', '$2y$10$VokmtXrIkNFEOQmTmJ3nn.7M.b1Rkz2DXi9Ff.najM1mWHWSM3LDu', 'Capt. MN MADHUSANKA', 'Captain', 'admin');

-- Default fuel stock row
INSERT IGNORE INTO fuel_stock (id, diesel, petrol) VALUES (1, 10000, 5000);

-- Reports
CREATE TABLE IF NOT EXISTS reports (
    id VARCHAR(20) PRIMARY KEY,
    type VARCHAR(30) NOT NULL,
    start_date DATE,
    end_date DATE,
    generated_by VARCHAR(100),
    content LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unit_id INT NULL, INDEX idx_reports_unit (unit_id)
);

-- WhatsApp Messages
CREATE TABLE IF NOT EXISTS whatsapp_messages (
    id VARCHAR(20) PRIMARY KEY,
    type VARCHAR(30),
    message TEXT,
    recipient VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unit_id INT NULL, INDEX idx_whatsapp_unit (unit_id)
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    username VARCHAR(100) NULL,
    action VARCHAR(80) NOT NULL,
    entity VARCHAR(80) NULL,
    entity_id VARCHAR(80) NULL,
    details JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    unit_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_created (created_at),
    INDEX idx_audit_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
