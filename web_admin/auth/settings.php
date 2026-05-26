<?php 
require_once '../config/session.php'; 
requireLogin(); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>User Settings - SC Management System</title> 
    <link rel="stylesheet" href="../assets/css/vendors/bootstrap.min.css"> 
    <link rel="stylesheet" href="../assets/css/main.css"> 
</head> 
<body> 
    <?php include '../includes/header.php'; ?> 
    <?php include '../includes/sidebar.php'; ?> 
    <div class="main-content"> 
        <div class="page-header"> 
            <h1>User Settings</h1> 
        </div> 
        <div class="settings-tabs"> 
            <ul class="tabs"> 
                <li class="active"><a href="#account">Account Settings</a></li> 
                <li><a href="#security">Security</a></li> 
                <li><a href="#notifications">Notifications</a></li> 
            </ul> 
            <div id="account" class="tab-content active"> 
                <form method="POST" action="auth/update-settings.php"> 
                    <div class="form-group"> 
                        <label>Language</label> 
                        <select name="language"> 
                            <option value="en">English</option> 
                            <option value="vi">Ti?ng Vi?t</option> 
                        </select> 
                    </div> 
                    <div class="form-group"> 
                        <label>Timezone</label> 
                        <select name="timezone"> 
                            <option value="Asia/Ho_Chi_Minh">Asia/Ho Chi Minh</option> 
                            <option value="UTC">UTC</option> 
                        </select> 
                    </div> 
                    <button type="submit" class="btn-primary">Save Settings</button> 
                </form> 
            </div> 
        </div> 
    </div> 
    <?php include '../includes/footer.php'; ?> 
</body> 
</html> 
