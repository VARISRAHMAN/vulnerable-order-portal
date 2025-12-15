<?php
// Kali Linux Database configuration with MariaDB
$host = 'localhost';
$username = 'root';
$password = 'root';  // Weak default password
$database = 'order_portal';

// MariaDB connection (compatible with MySQL functions)
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset for MariaDB compatibility
mysqli_set_charset($conn, "utf8");

// Create database and tables if they don't exist
$create_db = "CREATE DATABASE IF NOT EXISTS order_portal";
mysqli_query($conn, $create_db);
mysqli_select_db($conn, $database);

// Users table with plaintext passwords
$create_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),  -- Stored in plaintext
    email VARCHAR(100),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($conn, $create_users);

// Orders table
$create_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_name VARCHAR(100),
    quantity INT,
    price DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($conn, $create_orders);

// Insert default users with plaintext passwords
$insert_admin = "INSERT IGNORE INTO users (username, password, email, role) VALUES 
    ('admin', 'admin', 'admin@orderportal.com', 'admin'),
    ('customer', 'customer', 'customer@example.com', 'customer'),
    ('john', 'password123', 'john@example.com', 'customer'),
    ('jane', 'qwerty', 'jane@example.com', 'customer')";
mysqli_query($conn, $insert_admin);

// Insert sample orders
$insert_orders = "INSERT IGNORE INTO orders (user_id, product_name, quantity, price) VALUES 
    (2, 'Laptop', 1, 999.99),
    (2, 'Mouse', 2, 25.50),
    (3, 'Keyboard', 1, 75.00),
    (4, 'Monitor', 1, 299.99)";
mysqli_query($conn, $insert_orders);
?>