<?php
session_start();
include '../config/database.php';

// VULNERABILITY: No proper access control - user_id can be manipulated
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1);

// Initialize variables
$result = null;
$user = array('username' => 'Unknown');

if ($conn) {
    // Vulnerable query - no authorization check
    $query = "SELECT * FROM orders WHERE user_id = $user_id";
    $result = @mysqli_query($conn, $query);
    
    // Get user info
    $user_query = "SELECT username FROM users WHERE id = $user_id";
    $user_result = @mysqli_query($conn, $user_query);
    if ($user_result) {
        $user = mysqli_fetch_assoc($user_result) ?: array('username' => 'Unknown');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Orders</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="customer-panel">
        <h1>Orders for: <?php echo $user['username']; ?></h1>
        <p><strong>Vulnerability:</strong> Change user_id parameter to view other customers' orders!</p>
        
        <div class="vulnerability-demo">
            <p>Try these URLs:</p>
            <ul>
                <li><a href="?user_id=2">User ID 2 Orders</a></li>
                <li><a href="?user_id=3">User ID 3 Orders</a></li>
                <li><a href="?user_id=4">User ID 4 Orders</a></li>
            </ul>
        </div>
        
        <table>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
            <?php if ($result) while ($order = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['product_name']; ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td>$<?php echo $order['price']; ?></td>
                <td><?php echo $order['status']; ?></td>
                <td><?php echo $order['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <div class="actions">
            <a href="upload.php">Upload Receipt</a> |
            <a href="../index.php">Logout</a>
        </div>
    </div>
</body>
</html>