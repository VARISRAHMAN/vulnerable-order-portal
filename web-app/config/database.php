<?php
// Database configuration with error handling
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'order_portal';

// Create connection with error suppression
$conn = @mysqli_connect($host, $username, $password);

if (!$conn) {
    // Try to connect without database first
    $conn = @mysqli_connect($host, $username, $password);
    if (!$conn) {
        error_log("Database connection failed: " . mysqli_connect_error());
        // Don't die, continue with limited functionality
        $conn = null;
    }
}

if ($conn) {
    // Set charset
    @mysqli_set_charset($conn, "utf8");
    
    // Create database
    @mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $database");
    @mysqli_select_db($conn, $database);
}

if ($conn) {
    // Create tables with error handling
    $create_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE,
        password VARCHAR(255),
        email VARCHAR(100),
        role ENUM('admin', 'customer') DEFAULT 'customer',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    @mysqli_query($conn, $create_users);
    
    $create_orders = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        product_name VARCHAR(100),
        quantity INT,
        price DECIMAL(10,2),
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    @mysqli_query($conn, $create_orders);
    
    // Insert default data
    $insert_users = "INSERT IGNORE INTO users (username, password, email, role) VALUES 
        ('admin', 'admin', 'admin@orderportal.com', 'admin'),
        ('customer', 'customer', 'customer@example.com', 'customer'),
        ('john', 'password123', 'john@example.com', 'customer'),
        ('jane', 'qwerty', 'jane@example.com', 'customer')";
    @mysqli_query($conn, $insert_users);
    
    $insert_orders = "INSERT IGNORE INTO orders (user_id, product_name, quantity, price) VALUES 
        (2, 'Laptop', 1, 999.99),
        (2, 'Mouse', 2, 25.50),
        (3, 'Keyboard', 1, 75.00),
        (4, 'Monitor', 1, 299.99)";
    @mysqli_query($conn, $insert_orders);
}
?>
