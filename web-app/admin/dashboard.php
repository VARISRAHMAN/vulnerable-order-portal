<?php
// Simple admin dashboard - no database required
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .warning { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; color: #856404; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .actions { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; }
        .actions a { color: #007cba; text-decoration: none; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="warning">
            <strong>WARNING:</strong> This page has no authentication!
        </div>
        
        <div class="section">
            <h2>All Users</h2>
            <table>
                <tr><th>ID</th><th>Username</th><th>Password</th><th>Email</th><th>Role</th></tr>
                <tr><td>1</td><td>admin</td><td>admin</td><td>admin@orderportal.com</td><td>admin</td></tr>
                <tr><td>2</td><td>customer</td><td>customer</td><td>customer@example.com</td><td>customer</td></tr>
                <tr><td>3</td><td>john</td><td>password123</td><td>john@example.com</td><td>customer</td></tr>
                <tr><td>4</td><td>jane</td><td>qwerty</td><td>jane@example.com</td><td>customer</td></tr>
            </table>
        </div>
        
        <div class="section">
            <h2>All Orders</h2>
            <table>
                <tr><th>Order ID</th><th>Customer</th><th>Product</th><th>Quantity</th><th>Price</th><th>Status</th></tr>
                <tr><td>1</td><td>customer</td><td>Laptop</td><td>1</td><td>$999.99</td><td>pending</td></tr>
                <tr><td>2</td><td>customer</td><td>Mouse</td><td>2</td><td>$25.50</td><td>pending</td></tr>
                <tr><td>3</td><td>john</td><td>Keyboard</td><td>1</td><td>$75.00</td><td>pending</td></tr>
                <tr><td>4</td><td>jane</td><td>Monitor</td><td>1</td><td>$299.99</td><td>pending</td></tr>
            </table>
        </div>
        
        <div class="actions">
            <a href="../debug/phpinfo.php">View PHP Info</a> |
            <a href="../uploads/">Browse Uploads</a> |
            <a href="../api/user.php">User API</a>
        </div>
    </div>
</body>
</html>