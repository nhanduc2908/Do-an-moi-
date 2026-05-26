<?php 
require_once '../config/session.php'; 
requireLogin(); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>My Profile - SC Management System</title> 
    <link rel="stylesheet" href="../assets/css/vendors/bootstrap.min.css"> 
    <link rel="stylesheet" href="../assets/css/vendors/fontawesome.min.css"> 
    <link rel="stylesheet" href="../assets/css/main.css"> 
    <link rel="stylesheet" href="../assets/css/forms.css"> 
</head> 
<body> 
    <?php include '../includes/header.php'; ?> 
    <?php include '../includes/sidebar.php'; ?> 
    <div class="main-content"> 
        <div class="page-header"> 
            <h1>My Profile</h1> 
        </div> 
        <div class="profile-container"> 
            <div class="profile-avatar"> 
                <img src="../assets/images/default-avatar.png" alt="Avatar"> 
            </div> 
            <form method="POST" action="auth/update-profile.php" class="profile-form"> 
                <div class="form-row"> 
                    <div class="form-group"> 
                        <label>Full Name</label> 
                        <input type="text" name="fullname" value="^<?php echo $_SESSION['fullname'] ?? ''; ?^>"> 
                    </div> 
                    <div class="form-group"> 
                        <label>Email</label> 
                        <input type="email" name="email" value="^<?php echo $_SESSION['email'] ?? ''; ?^>"> 
                    </div> 
                </div> 
                <div class="form-group"> 
                    <label>Phone Number</label> 
                    <input type="tel" name="phone" value="^<?php echo $_SESSION['phone'] ?? ''; ?^>"> 
                </div> 
                <div class="form-group"> 
                    <label>Department</label> 
                    <select name="department"> 
                        <option value="IT">IT Security</option> 
                        <option value="Compliance">Compliance</option> 
                        <option value="Risk">Risk Management</option> 
                    </select> 
                </div> 
                <button type="submit" class="btn-primary">Update Profile</button> 
            </form> 
        </div> 
    </div> 
    <?php include '../includes/footer.php'; ?> 
</body> 
</html> 
