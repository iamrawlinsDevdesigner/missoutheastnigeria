
-- Miss South East Nigeria Database Structure
CREATE DATABASE IF NOT EXISTS miss_south_east;
USE miss_south_east;

-- Table for site settings (maintenance mode)
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_mode ENUM('live', 'maintenance') NOT NULL DEFAULT 'live'
);
INSERT INTO settings (site_mode) VALUES ('live');

-- Table for email verifications
CREATE TABLE IF NOT EXISTS email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    token VARCHAR(255) NOT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for contestants
CREATE TABLE IF NOT EXISTS contestants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    dob DATE NOT NULL,
    marital_status VARCHAR(50),
    nationality VARCHAR(100),
    city VARCHAR(100),
    town VARCHAR(100),
    religion VARCHAR(100),
    tribe VARCHAR(100),
    teller VARCHAR(100),
    phone VARCHAR(50),
    occupation VARCHAR(100),
    waist INT,
    height INT,
    burst INT,
    interest TEXT,
    hobby TEXT,
    meal VARCHAR(255),
    about TEXT,
    agree TINYINT(1),
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for admin users
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin user (username: admin, password: admin123)
INSERT INTO admins (username, password) VALUES ('admin', MD5('admin123'));
