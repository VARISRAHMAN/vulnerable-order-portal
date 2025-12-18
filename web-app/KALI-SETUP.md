# Kali Linux Setup Guide - Vulnerable Order Portal

## Prerequisites
- Kali Linux (latest rolling release)
- Root access or sudo privileges
- 2GB RAM minimum
- 5GB free disk space

## Quick Setup (Automated)

### Step 1: Clone/Download Project
```bash
# Download project files
cd /opt
git clone <repository> vulnerable-order-portal
cd vulnerable-order-portal
```

### Step 2: Run Setup Script
```bash
# Make script executable
chmod +x setup-kali.sh

# Run as root
sudo ./setup-kali.sh
```

### Step 3: Access Application
- URL: `http://localhost/order-portal/`
- Admin: `admin / admin`
- Customer: `customer / customer`

## Manual Setup (Step-by-Step)

### 1. Install Dependencies
```bash
# Update package lists
sudo apt update

# Install LAMP stack with MariaDB
sudo apt install -y apache2 mariadb-server php php-mysql php-cli php-common php-mbstring php-xml
```

### 2. Start Services
```bash
# Enable and start services
sudo systemctl enable apache2 mariadb
sudo systemctl start apache2 mariadb

# Verify services
sudo systemctl status apache2
sudo systemctl status mariadb
```

### 3. Configure MariaDB
```bash
# Set root password (intentionally weak)
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Test connection
mysql -u root -proot -e "SELECT VERSION();"
```

### 4. Deploy Web Application
```bash
# Create directory
sudo mkdir -p /var/www/html/order-portal

# Copy files
sudo cp -r web-app/* /var/www/html/order-portal/

# Set permissions
sudo chown -R www-data:www-data /var/www/html/order-portal/
sudo chmod -R 755 /var/www/html/order-portal/
sudo chmod -R 777 /var/www/html/order-portal/uploads/
```

### 5. Configure Apache
```bash
# Enable directory browsing (vulnerability)
echo "Options +Indexes" | sudo tee /var/www/html/order-portal/uploads/.htaccess

# Restart Apache
sudo systemctl restart apache2
```

### 6. Setup Database
```bash
# Create database and tables
mysql -u root -proot < setup.sql
```

## Kali-Specific Configurations

### MariaDB vs MySQL Differences
- **Package**: `mariadb-server` instead of `mysql-server`
- **Service**: Same `systemctl` commands work
- **Authentication**: MariaDB uses `mysql_native_password` by default
- **Compatibility**: 100% MySQL function compatibility

### Apache Configuration
- **Document Root**: `/var/www/html` (same as Ubuntu)
- **Config Path**: `/etc/apache2/sites-available/`
- **User/Group**: `www-data:www-data`
- **Logs**: `/var/log/apache2/`

### PHP Configuration
- **Version**: PHP 8.x (latest in Kali)
- **Extensions**: `php-mysql` works with MariaDB
- **Config**: `/etc/php/8.x/apache2/php.ini`

## Common Kali Issues & Solutions

### Issue 1: MariaDB Root Access
**Problem**: Cannot connect as root
**Solution**:
```bash
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';"
```

### Issue 2: Apache Permission Denied
**Problem**: 403 Forbidden errors
**Solution**:
```bash
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
```

### Issue 3: PHP Not Processing
**Problem**: PHP files download instead of execute
**Solution**:
```bash
sudo a2enmod php8.x
sudo systemctl restart apache2
```

### Issue 4: Database Connection Failed
**Problem**: mysqli_connect() errors
**Solution**:
```bash
sudo apt install php-mysql
sudo systemctl restart apache2
```

## Service Management

### Start/Stop Services
```bash
# Start services
sudo systemctl start apache2 mariadb

# Stop services
sudo systemctl stop apache2 mariadb

# Restart services
sudo systemctl restart apache2 mariadb

# Check status
sudo systemctl status apache2 mariadb
```

### Logs and Debugging
```bash
# Apache logs
sudo tail -f /var/log/apache2/error.log
sudo tail -f /var/log/apache2/access.log

# MariaDB logs
sudo tail -f /var/log/mysql/error.log

# PHP errors (enable in php.ini)
sudo tail -f /var/log/apache2/error.log | grep PHP
```

## Testing Vulnerabilities

### 1. Access Control Issues
```bash
# Direct admin access
curl http://localhost/order-portal/admin/dashboard.php

# Horizontal privilege escalation
curl "http://localhost/order-portal/customer/orders.php?user_id=3"
```

### 2. Information Disclosure
```bash
# PHP info page
curl http://localhost/order-portal/debug/phpinfo.php

# Directory listing
curl http://localhost/order-portal/uploads/
```

### 3. Database Access
```bash
# Connect to database
mysql -u root -proot

# View plaintext passwords
mysql -u root -proot -e "SELECT username, password FROM order_portal.users;"
```

## Network Access

### Local Access
- URL: `http://localhost/order-portal/`
- IP: `http://127.0.0.1/order-portal/`

### Network Access
```bash
# Find IP address
ip addr show | grep inet

# Access from other machines
http://[KALI-IP]/order-portal/
```

## Troubleshooting

### Reset Everything
```bash
# Stop services
sudo systemctl stop apache2 mariadb

# Remove application
sudo rm -rf /var/www/html/order-portal/

# Drop database
mysql -u root -proot -e "DROP DATABASE IF EXISTS order_portal;"

# Re-run setup
sudo ./setup-kali.sh
```

### Check Dependencies
```bash
# Verify installations
apache2 -v
mysql --version
php -v

# Check PHP modules
php -m | grep mysql
```

## Security Notes
- This setup intentionally creates vulnerabilities
- Use only in isolated lab environments
- Never deploy on production systems
- All credentials are intentionally weak