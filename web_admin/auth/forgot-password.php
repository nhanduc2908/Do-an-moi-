<?php 
session_start(); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Forgot Password - SC Management System</title> 
    <link rel="stylesheet" href="../assets/css/vendors/bootstrap.min.css"> 
    <link rel="stylesheet" href="../assets/css/auth.css"> 
</head> 
<body class="auth-page"> 
    <div class="auth-container"> 
        <div class="auth-box"> 
            <div class="auth-header"> 
                <img src="../assets/images/logo.png" alt="SC Management" class="auth-logo"> 
                <h2>Reset Password</h2> 
                <p>Enter your email to receive reset instructions</p> 
            </div> 
            <form method="POST" action="auth/reset-password.php" class="auth-form"> 
                <div class="form-group"> 
                    <label for="email">Email Address</label> 
                    <input type="email" id="email" name="email" required> 
                </div> 
                <button type="submit" class="btn-primary">Send Reset Link</button> 
            </form> 
            <div class="auth-footer"> 
                <a href="login.php">Back to Login</a> 
            </div> 
        </div> 
    </div> 
</body> 
</html> 
