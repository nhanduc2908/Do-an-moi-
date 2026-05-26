<?php 
require_once '../../config/database.php'; 
session_start(); 
 
// Redirect if already logged in with 2FA verified 
    header('Location: ../dashboard/index.php'); 
    exit(); 
} 
 
// Check if user needs 2FA 
if (!isset($_SESSION['temp_user_id'])) { 
    header('Location: ../login.php'); 
    exit(); 
} 
 
$error = ''; 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $otp = $_POST['otp'] ?? ''; 
 
    // Get user's 2FA secret 
    $stmt = $pdo-
    $stmt-
    $user = $stmt-
 
    // Verify OTP (simplified - use proper 2FA library in production) 
 
    if ($isValid) { 
        $_SESSION['two_factor_verified'] = true; 
        $_SESSION['user_id'] = $_SESSION['temp_user_id']; 
        unset($_SESSION['temp_user_id']); 
 
        // Update last login 
        $updateStmt = $pdo-
        $updateStmt-, $_SESSION['user_id']]); 
 
        header('Location: ../dashboard/index.php'); 
        exit(); 
    } else { 
        $error = 'Invalid OTP code. Please try again.'; 
    } 
} 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Verify OTP - SC Management System</title> 
    <link rel="stylesheet" href="../../assets/css/vendors/bootstrap.min.css"> 
    <link rel="stylesheet" href="../../assets/css/auth.css"> 
</head> 
<body class="auth-page"> 
    <div class="auth-container"> 
        <div class="auth-box"> 
            <div class="auth-header"> 
                <h2>Verify OTP</h2> 
                <p>Enter the 6-digit code from your authenticator app</p> 
            </div> 
            <?php if ($error): ?> 
                <div class="alert alert-danger"><?php echo $error; ?></div> 
            <?php endif; ?> 
            <form method="POST" action=""> 
                <div class="form-group"> 
                    <label for="otp">OTP Code</label> 
                    <input type="text" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" placeholder="123456" required autofocus> 
                </div> 
                <button type="submit" class="btn-primary">Verify OTP</button> 
            </form> 
        </div> 
    </div> 
</body> 
</html> 
