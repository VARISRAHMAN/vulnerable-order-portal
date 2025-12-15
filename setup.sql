-- Database setup for Kali Linux (MariaDB)
CREATE DATABASE IF NOT EXISTS order_portal;
USE order_portal;

-- Users table with plaintext passwords (vulnerability)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    email VARCHAR(100),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_name VARCHAR(100),
    quantity INT,
    price DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert vulnerable default users
INSERT IGNORE INTO users (username, password, email, role) VALUES 
    ('admin', 'admin', 'admin@orderportal.com', 'admin'),
    ('customer', 'customer', 'customer@example.com', 'customer'),
    ('john', 'password123', 'john@example.com', 'customer'),
    ('jane', 'qwerty', 'jane@example.com', 'customer');

-- Insert sample orders
INSERT IGNORE INTO orders (user_id, product_name, quantity, price) VALUES 
    (2, 'Laptop', 1, 999.99),
    (2, 'Mouse', 2, 25.50),
    (3, 'Keyboard', 1, 75.00),
    (4, 'Monitor', 1, 299.99);