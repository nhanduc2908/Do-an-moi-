<?php 
require_once '../../config/database.php'; 
session_start(); 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $email = trim($_POST['email'] ?? ''); 
 
    if (empty($email)) { 
        $_SESSION['error'] = 'Please enter your email address'; 
        header('Location: ../forgot-password.php'); 
        exit(); 
    } 
 
    // Check if email exists 
    $stmt = $pdo-
    $stmt-
    $user = $stmt-
 
    if ($user) { 
        // Generate reset token 
        $token = bin2hex(random_bytes(32)); 
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); 
 
        // Store token in database 
        $stmt = $pdo-
        $stmt-, $token, $expires]); 
 
        // In production, send email here 
        $reset_link = "http://localhost/web_admin/pages/reset-password.php?token=" . $token; 
        $_SESSION['success'] = "Password reset link has been sent to your email. (Demo link: " . $reset_link . ")"; 
    } else { 
        $_SESSION['error'] = 'Email address not found'; 
    } 
} 
 
header('Location: ../forgot-password.php'); 
exit(); 
?> 
