<?php 
require_once '../../config/session.php'; 
requireLogin(); 
require_once '../../config/database.php'; 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Users Management - SC Management System</title> 
    <link rel="stylesheet" href="../../assets/css/vendors/bootstrap.min.css"> 
    <link rel="stylesheet" href="../../assets/css/vendors/datatables.min.css"> 
    <link rel="stylesheet" href="../../assets/css/main.css"> 
</head> 
<body> 
    <?php include '../../includes/header.php'; ?> 
    <?php include '../../includes/sidebar.php'; ?> 
    <div class="main-content"> 
        <div class="page-header"> 
            <h1>Users Management</h1> 
            <a href="create.php" class="btn-primary">+ Add New User</a> 
        </div> 
        <table id="usersTable" class="datatable"> 
            <thead> 
                <tr> 
                    <th>ID</th> 
                    <th>Username</th> 
                    <th>Email</th> 
                    <th>Full Name</th> 
                    <th>Role</th> 
                    <th>Status</th> 
                    <th>Last Login</th> 
                    <th>Actions</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php 
                $stmt = $pdo-
                while($user = $stmt- { 
                    echo "^<tr^>"; 
                    echo "^<td^>" . $user['id'] . "^</td^>"; 
                    echo "^<td^>" . htmlspecialchars($user['username']) . "^</td^>"; 
                    echo "^<td^>" . htmlspecialchars($user['email']) . "^</td^>"; 
                    echo "^<td^>" . htmlspecialchars($user['fullname']) . "^</td^>"; 
                    echo "^<td^>" . $user['role_name'] . "^</td^>"; 
                    echo "^<td^>^<span class='status-badge " . $user['status'] . "'^>" . $user['status'] . "^</span^^></td^>"; 
                    echo "^<td^>" . $user['last_login'] . "^</td^>"; 
                    echo "^<td^>"; 
                    echo "^<a href='view.php?id=" . $user['id'] . "' class='btn-sm'^>View^</a^>"; 
                    echo "^<a href='edit.php?id=" . $user['id'] . "' class='btn-sm'^>Edit^</a^>"; 
                    echo "^<a href='delete.php?id=" . $user['id'] . "' class='btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'^>Delete^</a^>"; 
                    echo "^</td^>"; 
                    echo "^</tr^>"; 
                } 
                ?> 
            </tbody> 
        </table> 
    </div> 
    <?php include '../../includes/footer.php'; ?> 
    <script src="../../assets/js/vendors/datatables.min.js"></script> 
    <script> 
        $(document).ready(function() { 
            $('#usersTable').DataTable(); 
        }); 
    </script> 
</body> 
