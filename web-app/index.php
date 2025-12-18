<?php
session_start();
include 'config/database.php';

if ($_POST && $conn) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Vulnerable SQL injection (intentional)
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = @mysqli_query($conn, $query);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
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
} elseif ($_POST && !$conn) {
    $error = "Database connection error";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>🍔 Order Portal - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-1.8.3.min.js"></script>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side - Background Image -->
        <div class="login-left">
            <div class="overlay"></div>
            <div class="welcome-content">
                <div class="logo">🍔</div>
                <h1>Welcome Back</h1>
                <p>Login to manage your burger orders fast & easy</p>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-card">
                <div class="card-header">
                    <h2>Sign In</h2>
                    <p>Enter your credentials to access your account</p>
                </div>
                
                <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
                
                <form method="POST" class="login-form">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    
                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Remember me
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="login-btn">Login</button>
                </form>
                
                <!-- Demo Accounts -->
                <div class="demo-section">
                    <h4>Demo Accounts</h4>
                    <div class="demo-grid">
                        <div class="demo-item admin">
                            <span class="demo-role">🔐 Admin</span>
                            <span class="demo-creds">admin / admin</span>
                        </div>
                        <div class="demo-item customer">
                            <span class="demo-role">👤 Customer</span>
                            <span class="demo-creds">customer / customer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>