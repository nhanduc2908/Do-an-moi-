<?php 
require_once '../../config/database.php'; 
session_start(); 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $username = trim($_POST['username'] ?? ''); 
    $password = $_POST['password'] ?? ''; 
 
    if (empty($username) || empty($password)) { 
        $_SESSION['error'] = 'Please enter username and password'; 
        header('Location: ../login.php'); 
        exit(); 
    } 
 
    $stmt = $pdo-
    $stmt-, $username]); 
    $user = $stmt-
 
        if ($user['status'] !== 'active') { 
            $_SESSION['error'] = 'Account is locked or inactive'; 
        } elseif ($user['two_factor_enabled']) { 
            // Store temp user ID and redirect to OTP verification 
            $_SESSION['temp_user_id'] = $user['id']; 
            $_SESSION['temp_email'] = $user['email']; 
            header('Location: verify-otp.php'); 
            exit(); 
        } else { 
            // No 2FA, login directly 
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username']; 
            $_SESSION['fullname'] = $user['fullname']; 
            $_SESSION['email'] = $user['email']; 
            $_SESSION['role_id'] = $user['role_id']; 
 
            $updateStmt = $pdo-
            $updateStmt-, $user['id']]); 
 
            header('Location: ../dashboard/index.php'); 
            exit(); 
        } 
    } else { 
        $_SESSION['error'] = 'Invalid username or password'; 
    } 
    header('Location: ../login.php'); 
    exit(); 
} 
?> 
