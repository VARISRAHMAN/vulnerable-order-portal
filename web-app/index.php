<?php
session_start();
include 'config/database.php';

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Vulnerable SQL injection
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        
        if ($row['role'] == 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: customer/orders.php');
        }
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Portal - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-1.8.3.min.js"></script>
</head>
<body>
    <div class="login-container">
        <h2>Order Portal Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        
        <div class="demo-accounts">
            <h3>Demo Accounts:</h3>
            <p>Admin: admin / admin</p>
            <p>Customer: customer / customer</p>
        </div>
    </div>
</body>
</html>