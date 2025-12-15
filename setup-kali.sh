#!/bin/bash
# Kali Linux Setup Script for Vulnerable Order Portal

set -e

echo "=== Vulnerable Order Portal - Kali Linux Setup ==="

# Update package lists
echo "[1/6] Updating package lists..."
apt update

# Install LAMP stack (MariaDB instead of MySQL)
echo "[2/6] Installing Apache, MariaDB, PHP..."
apt install -y apache2 mariadb-server php php-mysql php-cli php-common php-mbstring php-xml

# Start and enable services
echo "[3/6] Starting services..."
systemctl enable apache2
systemctl enable mariadb
systemctl start apache2
systemctl start mariadb

# Configure MariaDB with weak credentials (intentional vulnerability)
echo "[4/6] Configuring MariaDB..."
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';" 2>/dev/null || \
mysql -e "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('root');"
mysql -e "FLUSH PRIVILEGES;"

# Copy web application files
echo "[5/6] Deploying web application..."
mkdir -p /var/www/html/order-portal
cp -r web-app/* /var/www/html/order-portal/

# Set proper permissions
chown -R www-data:www-data /var/www/html/order-portal/
chmod -R 755 /var/www/html/order-portal/
chmod -R 777 /var/www/html/order-portal/uploads/

# Enable directory browsing (vulnerability)
echo "Options +Indexes" > /var/www/html/order-portal/uploads/.htaccess

# Configure Apache (remove security headers intentionally)
cat > /etc/apache2/sites-available/000-default.conf << 'EOF'
<VirtualHost *:80>
    DocumentRoot /var/www/html
    ServerName localhost
    
    # Intentionally weak configuration
    ServerTokens Full
    ServerSignature On
    
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

# Restart Apache to apply changes
systemctl restart apache2

# Create database and tables
echo "[6/6] Setting up database..."
mysql -u root -proot << 'EOF'
CREATE DATABASE IF NOT EXISTS order_portal;
USE order_portal;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    email VARCHAR(100),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_name VARCHAR(100),
    quantity INT,
    price DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT IGNORE INTO users (username, password, email, role) VALUES 
    ('admin', 'admin', 'admin@orderportal.com', 'admin'),
    ('customer', 'customer', 'customer@example.com', 'customer'),
    ('john', 'password123', 'john@example.com', 'customer'),
    ('jane', 'qwerty', 'jane@example.com', 'customer');

INSERT IGNORE INTO orders (user_id, product_name, quantity, price) VALUES 
    (2, 'Laptop', 1, 999.99),
    (2, 'Mouse', 2, 25.50),
    (3, 'Keyboard', 1, 75.00),
    (4, 'Monitor', 1, 299.99);
EOF

# Get IP address
IP=$(ip route get 1.1.1.1 | awk '{print $7}' | head -1)

echo ""
echo "=== Setup Complete! ==="
echo "Web Application URL: http://$IP/order-portal/"
echo "Admin Login: admin / admin"
echo "Customer Login: customer / customer"
echo ""
echo "Services Status:"
systemctl is-active apache2 && echo "✓ Apache2: Running" || echo "✗ Apache2: Failed"
systemctl is-active mariadb && echo "✓ MariaDB: Running" || echo "✗ MariaDB: Failed"
echo ""
echo "Database Access: mysql -u root -proot"
echo "Logs: tail -f /var/log/apache2/error.log"