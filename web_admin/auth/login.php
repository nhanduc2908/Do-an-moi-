<?php 
require_once '../../config/database.php'; 
session_start(); 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $username = trim($_POST['username'] ?? ''); 
    $password = $_POST['password'] ?? ''; 
    $remember = isset($_POST['remember']); 
 
    if (empty($username) || empty($password)) { 
        $_SESSION['error'] = 'Please enter username and password'; 
        header('Location: ../login.php'); 
        exit(); 
    } 
 
    // Check user credentials 
    $stmt = $pdo-
    $stmt-, $username]); 
    $user = $stmt-
 
        if ($user['status'] !== 'active') { 
            $_SESSION['error'] = 'Account is locked or inactive'; 
            header('Location: ../login.php'); 
            exit(); 
        } 
 
        // Set session variables 
        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['username'] = $user['username']; 
        $_SESSION['fullname'] = $user['fullname']; 
        $_SESSION['email'] = $user['email']; 
        $_SESSION['role_id'] = $user['role_id']; 
 
        // Update last login 
        $updateStmt = $pdo-
        $updateStmt-, $user['id']]); 
 
        // Handle remember me 
        if ($remember) { 
            $token = bin2hex(random_bytes(32)); 
            setcookie('remember_token', $token, time + 86400 * 30, '/'); 
            // Store token in database 
        } 
 
        header('Location: ../dashboard/index.php'); 
        exit(); 
    } else { 
        $_SESSION['error'] = 'Invalid username or password'; 
        header('Location: ../login.php'); 
        exit(); 
    } 
} 
?> 
