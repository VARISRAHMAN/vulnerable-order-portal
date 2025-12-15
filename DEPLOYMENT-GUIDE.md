# Vulnerable Order Portal - Kali Linux Deployment

## Prerequisites
- Kali Linux (latest rolling)
- Root access or sudo privileges
- 2GB RAM minimum
- 5GB free disk space

## Quick Setup

### Automated Installation
```bash
# Clone project
cd /opt
git clone <repository> vulnerable-order-portal
cd vulnerable-order-portal

# Run setup script
sudo chmod +x setup-kali.sh
sudo ./setup-kali.sh
```

### Manual Installation
```bash
# Install dependencies
sudo apt update
sudo apt install -y apache2 mariadb-server php php-mysql

# Start services
sudo systemctl enable apache2 mariadb
sudo systemctl start apache2 mariadb

# Configure MariaDB
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';"

# Deploy application
sudo cp -r web-app/* /var/www/html/order-portal/
sudo chown -R www-data:www-data /var/www/html/order-portal/

# Setup database
mysql -u root -proot < setup.sql
```

## Access Application

### Local Access
- URL: `http://localhost/order-portal/`
- Admin: `admin / admin`
- Customer: `customer / customer`

### Network Access
```bash
# Find IP
ip addr show | grep inet

# Access from network
http://[KALI-IP]/order-portal/
```

### Service Management
```bash
# Check services
sudo systemctl status apache2 mariadb

# Restart services
sudo systemctl restart apache2 mariadb
```

### Database Access
```bash
# Connect to MariaDB
mysql -u root -proot

# Use database
USE order_portal;
SHOW TABLES;
```

## Testing Vulnerabilities

### 1. Broken Access Control
```bash
# Access admin panel without auth
curl http://localhost/order-portal/admin/dashboard.php

# View other users' orders
curl "http://localhost/order-portal/customer/orders.php?user_id=3"
```

### 2. Security Misconfiguration
```bash
# View PHP info
curl http://localhost/order-portal/debug/phpinfo.php

# Browse uploads directory
curl http://localhost/order-portal/uploads/
```

### 3. Vulnerable Components
```bash
# Check jQuery version (vulnerable 1.8.3)
curl http://localhost/order-portal/js/jquery-1.8.3.min.js
```

### 4. Cryptographic Failures
```bash
# Check plaintext passwords in database
mysql -u root -proot -e "SELECT username, password FROM order_portal.users;"
```

## Troubleshooting

### Service Issues
```bash
# Check logs
sudo tail -f /var/log/apache2/error.log
sudo tail -f /var/log/mysql/error.log

# Fix permissions
sudo chown -R www-data:www-data /var/www/html/order-portal/
sudo chmod -R 755 /var/www/html/order-portal/
```

### Database Issues
```bash
# Reset MariaDB root password
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';"

# Recreate database
mysql -u root -proot < setup.sql
```

### Network Issues
```bash
# Check listening ports
sudo netstat -tlnp | grep :80
sudo netstat -tlnp | grep :3306

# Restart services
sudo systemctl restart apache2 mariadb
```

### Reset Everything
```bash
# Clean installation
sudo rm -rf /var/www/html/order-portal/
mysql -u root -proot -e "DROP DATABASE IF EXISTS order_portal;"
sudo ./setup-kali.sh
```

## Security Warning
This application contains intentional vulnerabilities. Use only in isolated lab environments for educational purposes.