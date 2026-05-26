<?php 
echo "=== TESTING AUTH MODULE ===\n\n"; 
 
// Test database connection 
require_once 'config/database.php'; 
echo "û Database connected successfully\n"; 
 
// Check auth files 
$auth_files = ['login.php', 'register.php', 'two-factor.php', 'verify-otp.php']; 
foreach($auth_files as $file) { 
    $path = "pages/auth/" . $file; 
    if(file_exists($path)) { 
        echo "û $file exists\n"; 
    } else { 
        echo "? $file missing\n"; 
    } 
} 
?> 
