<?php 
require_once '../../config/database.php'; 
session_start(); 
 
// Redirect if already logged in 
if (isset($_SESSION['user_id'])) { 
    header('Location: ../dashboard/index.php'); 
    exit(); 
} 
 
$error = ''; 
$success = ''; 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $username = trim($_POST['username'] ?? ''); 
    $email = trim($_POST['email'] ?? ''); 
    $password = $_POST['password'] ?? ''; 
    $confirm_password = $_POST['confirm_password'] ?? ''; 
    $fullname = trim($_POST['fullname'] ?? ''); 
    $department = $_POST['department'] ?? ''; 
 
    // Validation 
    if (empty($username) || empty($email) || empty($password)) { 
        $error = 'Please fill in all required fields'; 
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        $error = 'Invalid email format'; 
        $error = 'Password must be at least 8 characters'; 
    } elseif ($password !== $confirm_password) { 
        $error = 'Passwords do not match'; 
    } else { 
        // Check if username or email exists 
        $stmt = $pdo-
        $stmt-, $email]); 
        if ($stmt- { 
            $error = 'Username or email already exists'; 
        } else { 
            // Create new user (default role: user) 
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
                INSERT INTO users (username, email, password, fullname, department, role_id, status) 
                VALUES (?, ?, ?, ?, ?, 5, 'active') 
            $stmt-, $email, $hashed_password, $fullname, $department]); 
            $success = 'Registration successful! You can now login.'; 
        } 
    } 
} 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Register - SC Management System</title> 
    <link rel="stylesheet" href="../../assets/css/vendors/bootstrap.min.css"> 
    <link rel="stylesheet" href="../../assets/css/vendors/toastr.min.css"> 
    <link rel="stylesheet" href="../../assets/css/auth.css"> 
    <style> 
        .register-container { max-width: 500px; margin: 50px auto; padding: 20px; } 
        .form-group { margin-bottom: 20px; } 
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; } 
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; } 
        .btn-register { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; } 
        .btn-register:hover { background: #0056b3; } 
        .password-strength { margin-top: 5px; font-size: 12px; } 
        .strength-weak { color: #dc3545; } 
        .strength-medium { color: #ffc107; } 
        .strength-strong { color: #28a745; } 
    </style> 
</head> 
<body class="auth-page"> 
    <div class="register-container"> 
        <div class="auth-box"> 
            <div class="auth-header"> 
                <img src="../../assets/images/logo.png" alt="SC Management" class="auth-logo"> 
                <h2>Create Account</h2> 
                <p>Register for SC Management System</p> 
            </div> 
 
            <?php if ($error): ?> 
                <div class="alert alert-danger"><?php echo $error; ?></div> 
            <?php endif; ?> 
            <?php if ($success): ?> 
                <div class="alert alert-success"><?php echo $success; ?></div> 
            <?php endif; ?> 
 
            <form method="POST" action="" id="registerForm"> 
                <div class="form-group"> 
                    <label for="username">Username *</label> 
                    <input type="text" id="username" name="username" required> 
                </div> 
                <div class="form-group"> 
                    <label for="email">Email *</label> 
                    <input type="email" id="email" name="email" required> 
                </div> 
                <div class="form-group"> 
                    <label for="fullname">Full Name</label> 
                    <input type="text" id="fullname" name="fullname"> 
                </div> 
                <div class="form-group"> 
                    <label for="department">Department</label> 
                    <select id="department" name="department"> 
                        <option value="">Select Department</option> 
                        <option value="IT">IT Security</option> 
                        <option value="Compliance">Compliance</option> 
                        <option value="Risk">Risk Management</option> 
                        <option value="Audit">Internal Audit</option> 
                        <option value="Legal">Legal</option> 
                    </select> 
                </div> 
                <div class="form-group"> 
                    <label for="password">Password *</label> 
                    <input type="password" id="password" name="password" required> 
                    <div class="password-strength" id="passwordStrength"></div> 
                </div> 
                <div class="form-group"> 
                    <label for="confirm_password">Confirm Password *</label> 
                    <input type="password" id="confirm_password" name="confirm_password" required> 
                </div> 
                <button type="submit" class="btn-register">Register</button> 
            </form> 
            <div class="auth-footer"> 
                <p>Already have an account? <a href="../login.php">Login here</a></p> 
            </div> 
        </div> 
    </div> 
 
    <script src="../../assets/js/vendors/jquery.min.js"></script> 
    <script> 
        $('#password').on('keyup', function() { 
            var password = $(this).val(); 
            var strength = 0; 
            if (password.length  strength++; 
            if (password.match(/[a-z]+/)) strength++; 
            if (password.match(/[A-Z]+/)) strength++; 
            if (password.match(/[0-9]+/)) strength++; 
 
            var strengthText = ''; 
            var strengthClass = ''; 
            if (password.length === 0) { 
                strengthText = ''; 
            } else if (strength < 3) { 
                strengthText = 'Weak password'; 
                strengthClass = 'strength-weak'; 
            } else if (strength < 5) { 
                strengthText = 'Medium password'; 
                strengthClass = 'strength-medium'; 
            } else { 
                strengthText = 'Strong password'; 
                strengthClass = 'strength-strong'; 
            } 
            $('#passwordStrength').html(strengthText).attr('class', 'password-strength ' + strengthClass); 
        }); 
    </script> 
</body> 
</html> 
