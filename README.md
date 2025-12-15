# Vulnerable Order Portal

## Overview
Intentionally vulnerable web application for security testing and education. Contains OWASP Top 10 vulnerabilities including SQL injection, broken access control, and cryptographic failures.

## Features
- **Customer Order System**: Login, view orders, file uploads
- **Admin Dashboard**: Unprotected admin panel with full data access
- **REST API**: User management with privilege escalation
- **Multiple Vulnerabilities**: Mapped to OWASP Top 10 2021

## Quick Start

### Requirements
- Kali Linux (latest rolling)
- 2GB RAM, 5GB disk space
- Root/sudo access

### One-Command Setup
```bash
cd /opt && git clone <repo> vulnerable-order-portal
cd vulnerable-order-portal && sudo ./setup-kali.sh
```

### Access
- **URL**: `http://localhost/order-portal/`
- **Admin**: `admin / admin`
- **Customer**: `customer / customer`
- **Database**: `mysql -u root -proot`

## Vulnerability Map

### OWASP A01 - Broken Access Control
- **Admin Panel**: `/admin/dashboard.php` - No authentication
- **Horizontal Escalation**: `/customer/orders.php?user_id=X`
- **Vertical Escalation**: `/api/user.php` - Role manipulation

### OWASP A02 - Cryptographic Failures
- **Plaintext Passwords**: Database stores passwords in clear text
- **Weak Hashing**: MD5 used for session tokens
- **No HTTPS**: All traffic unencrypted

### OWASP A03 - Injection
- **SQL Injection**: Login form vulnerable to SQLi
- **No Input Validation**: Direct SQL query construction

### OWASP A05 - Security Misconfiguration
- **Default Credentials**: `admin:admin`, `root:root`
- **Debug Pages**: `/debug/phpinfo.php` exposed
- **Directory Listing**: `/uploads/` browsable
- **Missing Headers**: No security headers configured

### OWASP A06 - Vulnerable Components
- **jQuery 1.8.3**: Known XSS vulnerabilities
- **File Upload**: Path traversal in upload handler
- **Outdated PHP**: Using older PHP version



## Testing Examples

### SQL Injection
```bash
# Login bypass
Username: admin' OR '1'='1' --
Password: anything
```

### Access Control
```bash
# Direct admin access
curl http://localhost/order-portal/admin/dashboard.php

# View other user orders
curl "http://localhost/order-portal/customer/orders.php?user_id=3"
```

### Privilege Escalation
```bash
# Escalate user to admin via API
curl -X POST http://localhost/order-portal/api/user.php \
  -H "Content-Type: application/json" \
  -d '{"user_id": 2, "role": "admin"}'
```

### File Upload
```bash
# Upload malicious file with path traversal
# Filename: ../../../shell.php
```

## Documentation
- **Setup Guide**: `KALI-SETUP.md` - Detailed installation instructions
- **Deployment**: `DEPLOYMENT-GUIDE.md` - Quick deployment steps
- **Vulnerabilities**: `VULNERABILITIES.md` - Complete vulnerability catalog

## Tools Integration
- **Burp Suite**: Web application testing
- **OWASP ZAP**: Automated scanning
- **SQLMap**: SQL injection testing
- **Metasploit**: Exploitation framework

## Educational Use
This application is designed for:
- Security training and education
- Penetration testing practice
- Vulnerability assessment learning
- OWASP Top 10 demonstration

## ⚠️ Security Warning
**FOR EDUCATIONAL USE ONLY**
Contains intentional vulnerabilities. Use only in isolated lab environments. Never deploy on production systems or networks you don't own.