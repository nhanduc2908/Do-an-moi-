<?php 
require_once '../../config/database.php'; 
require_once '../../config/session.php'; 
requireLogin(); 
 
// Generate 2FA secret if not exists 
if (empty($_SESSION['two_factor_secret'])) { 
    $_SESSION['two_factor_secret'] = bin2hex(random_bytes(20)); 
} 
 
$secret = $_SESSION['two_factor_secret']; 
$qrCodeUrl = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=otpauth://totp/SCManagement:" . urlencode($_SESSION['email']) . "?secret=" . $secret . "&issuer=SCManagement"; 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $otp = $_POST['otp'] ?? ''; 
    // In production, verify OTP with Google Authenticator library 
 
    if ($isValid) { 
        // Enable 2FA for user 
        $stmt = $pdo-
        $stmt-, $_SESSION['user_id']]); 
        $_SESSION['success'] = 'Two-factor authentication enabled successfully!'; 
        unset($_SESSION['two_factor_secret']); 
        header('Location: ../settings.php'); 
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
    <title>Two-Factor Authentication - SC Management System</title> 
    <link rel="stylesheet" href="../../assets/css/vendors/bootstrap.min.css"> 
    <link rel="stylesheet" href="../../assets/css/auth.css"> 
</head> 
<body class="auth-page"> 
    <div class="auth-container"> 
        <div class="auth-box"> 
            <div class="auth-header"> 
                <h2>Two-Factor Authentication</h2> 
                <p>Enhance your account security</p> 
            </div> 
            <?php if (isset($error)): ?> 
                <div class="alert alert-danger"><?php echo $error; ?></div> 
            <?php endif; ?> 
            <div class="qr-container" style="text-align: center; margin: 20px 0;"> 
                <img src="^<?php echo $qrCodeUrl; ?^>" alt="QR Code"> 
                <p>Scan this QR code with Google Authenticator or any 2FA app</p> 
                <div class="secret-key"> 
                    <strong>Secret Key: </strong> <code><?php echo $secret; ?></code> 
                </div> 
            </div> 
            <form method="POST" action=""> 
                <div class="form-group"> 
                    <label for="otp">Enter 6-digit OTP</label> 
                    <input type="text" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" required> 
                </div> 
                <button type="submit" class="btn-primary">Verify and Enable 2FA</button> 
            </form> 
            <div class="auth-footer"> 
                <a href="../settings.php">Skip for now</a> 
            </div> 
        </div> 
    </div> 
</body> 
</html> 
