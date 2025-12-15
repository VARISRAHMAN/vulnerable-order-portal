<?php
// VULNERABILITY: No authentication check - direct access allowed
include '../config/database.php';

// Get all users and orders without proper authorization
$users_query = "SELECT * FROM users";
$users_result = mysqli_query($conn, $users_query);

$orders_query = "SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id";
$orders_result = mysqli_query($conn, $orders_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="admin-panel">
        <h1>Admin Dashboard</h1>
        <p><strong>WARNING:</strong> This page has no authentication!</p>
        
        <div class="section">
            <h2>All Users</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
                <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['password']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        
        <div class="section">
            <h2>All Orders</h2>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
                <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['username']; ?></td>
                    <td><?php echo $order['product_name']; ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td>$<?php echo $order['price']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                </tr>
                <?php endwhile; ?>
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