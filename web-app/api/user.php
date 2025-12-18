<?php
header('Content-Type: application/json');
include '../config/database.php';

// VULNERABILITY: No authentication or authorization checks
// VULNERABILITY: Accepts role changes via API

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Database connection error']);
        exit;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['user_id']) && isset($input['role'])) {
        $user_id = $input['user_id'];
        $role = $input['role'];
        
        // VULNERABILITY: Direct role update without validation
        $query = "UPDATE users SET role = '$role' WHERE id = $user_id";
        
        if (@mysqli_query($conn, $query)) {
            echo json_encode([
                'success' => true,
                'message' => "User role updated to $role",
                'user_id' => $user_id
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Database error'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Missing parameters'
        ]);
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!$conn) {
        echo json_encode(['error' => 'Database connection error']);
        exit;
    }
    
    // VULNERABILITY: Exposes all user data without authentication
    $user_id = isset($_GET['id']) ? $_GET['id'] : null;
    
    if ($user_id) {
        $query = "SELECT * FROM users WHERE id = $user_id";
        $result = @mysqli_query($conn, $query);
        
        if ($result && $user = mysqli_fetch_assoc($result)) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } else {
        // Return all users
        $query = "SELECT * FROM users";
        $result = @mysqli_query($conn, $query);
        $users = [];
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }
        
        echo json_encode($users);
    }
}
?>