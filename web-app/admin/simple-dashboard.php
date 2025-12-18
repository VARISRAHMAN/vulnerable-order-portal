<?php
// Simple admin dashboard without database dependencies
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .warning { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .section { margin: 30px 0; }
        .actions a { color: #007cba; text-decoration: none; margin: 0 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Admin Dashboard</h1>
        <div class="warning">
            <strong>⚠️ WARNING:</strong> This page has no authentication - Direct access vulnerability!
        </div>
        
        <div class="section">
            <h2>System Status</h2>
            <p>✅ Admin panel accessible without login</p>
            <p>✅ Vulnerable to direct URL access</p>
            <p>✅ No session validation</p>
        </div>
        
        <div class="section">
            <h2>Demo Users (Hardcoded)</h2>
            <table>
                <tr><th>ID</th><th>Username</th><th>Password</th><th>Role</th></tr>
                <tr><td>1</td><td>admin</td><td>admin</td><td>admin</td></tr>
                <tr><td>2</td><td>customer</td><td>customer</td><td>customer</td></tr>
                <tr><td>3</td><td>john</td><td>password123</td><td>customer</td></tr>
                <tr><td>4</td><td>jane</td><td>qwerty</td><td>customer</td></tr>
            </table>
        </div>
        
        <div class="section">
            <h2>Demo Orders (Hardcoded)</h2>
            <table>
                <tr><th>Order ID</th><th>Customer</th><th>Product</th><th>Price</th><th>Status</th></tr>
                <tr><td>1</td><td>customer</td><td>Burger Combo</td><td>$12.99</td><td>completed</td></tr>
                <tr><td>2</td><td>john</td><td>Pizza Special</td><td>$15.50</td><td>pending</td></tr>
                <tr><td>3</td><td>jane</td><td>Chicken Wings</td><td>$8.99</td><td>delivered</td></tr>
            </table>
        </div>
        
        <div class="actions">
            <a href="../index.php">← Back to Login</a> |
            <a href="../debug/phpinfo.php">PHP Info</a> |
            <a href="../uploads/">Browse Uploads</a> |
            <a href="../api/user.php">User API</a>
        </div>
        
        <div class="section">
            <h3>🔍 Vulnerability Testing</h3>
            <p><strong>Access Control:</strong> This page loads without authentication</p>
            <p><strong>Direct URL:</strong> http://localhost/order-portal/admin/dashboard.php</p>
            <p><strong>Impact:</strong> Unauthorized access to admin functions</p>
        </div>
    </div>
</body>
</html>