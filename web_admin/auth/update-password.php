<?php 
require_once '../../config/database.php'; 
session_start(); 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $token = $_POST['token'] ?? ''; 
    $password = $_POST['password'] ?? ''; 
    $confirm = $_POST['confirm_password'] ?? ''; 
 
    if ($password !== $confirm) { 
        $_SESSION['error'] = 'Passwords do not match'; 
        header("Location: ../reset-password.php?token=" . urlencode($token)); 
        exit(); 
    } 
 
    // Verify token 
    $stmt = $pdo-
    $stmt-
    $reset = $stmt-
 
    if ($reset) { 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
        $stmt = $pdo-
        $stmt-, $reset['email']]); 
 
        // Mark token as used 
        $stmt = $pdo-
        $stmt-
 
        $_SESSION['success'] = 'Password has been reset successfully. Please login.'; 
        header('Location: ../login.php'); 
    } else { 
        $_SESSION['error'] = 'Invalid or expired reset token'; 
        header('Location: ../forgot-password.php'); 
    } 
    exit(); 
} 
?> 
