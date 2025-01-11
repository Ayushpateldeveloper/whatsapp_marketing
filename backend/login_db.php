<?php
session_start();
require_once 'database.php';

// Ensure proper JSON content type header
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    
    if(empty($username) || empty($password)) {
        die(json_encode([
            'success' => false,
            'message' => 'Username and password are required'
        ]));
    }
    
    try {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? AND status = 'active'");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            die(json_encode([
                'success' => true,
                'message' => 'Login successful'
            ]));
        } else {
            die(json_encode([
                'success' => false,
                'message' => 'Invalid username or password'
            ]));
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        die(json_encode([
            'success' => false,
            'message' => 'Database error occurred'
        ]));
    }
} else {
    die(json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]));
}
?>

