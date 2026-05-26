-- SC Management System Database Schema 
 
CREATE DATABASE IF NOT EXISTS `sc_management`; 
USE `sc_management`; 
 
-- Users table 
CREATE TABLE `users` ( 
  `id` INT PRIMARY KEY AUTO_INCREMENT, 
  `username` VARCHAR(50) UNIQUE NOT NULL, 
  `email` VARCHAR(100) UNIQUE NOT NULL, 
  `password` VARCHAR(255) NOT NULL, 
  `fullname` VARCHAR(100), 
  `phone` VARCHAR(20), 
  `department` VARCHAR(50), 
  `role_id` INT, 
  `language` VARCHAR(10) DEFAULT 'en', 
  `timezone` VARCHAR(50) DEFAULT 'Asia/Ho_Chi_Minh', 
  `two_factor_enabled` BOOLEAN DEFAULT FALSE, 
  `two_factor_secret` VARCHAR(255), 
  `status` ENUM('active','inactive','locked') DEFAULT 'active', 
  `last_login` DATETIME, 
  `last_ip` VARCHAR(45), 
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
); 
 
-- Password reset tokens table 
CREATE TABLE `password_resets` ( 
  `id` INT PRIMARY KEY AUTO_INCREMENT, 
  `email` VARCHAR(100) NOT NULL, 
  `token` VARCHAR(255) NOT NULL, 
  `expires_at` DATETIME NOT NULL, 
  `used` BOOLEAN DEFAULT FALSE, 
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
); 
 
-- Roles table 
CREATE TABLE `roles` ( 
  `id` INT PRIMARY KEY AUTO_INCREMENT, 
  `name` VARCHAR(50) UNIQUE NOT NULL, 
  `description` TEXT, 
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
); 
 
-- Permissions table 
CREATE TABLE `permissions` ( 
  `id` INT PRIMARY KEY AUTO_INCREMENT, 
  `name` VARCHAR(100) UNIQUE NOT NULL, 
  `module` VARCHAR(50), 
  `description` TEXT 
); 
 
-- Role permissions junction table 
CREATE TABLE `role_permissions` ( 
  `role_id` INT, 
  `permission_id` INT, 
  PRIMARY KEY (`role_id`, `permission_id`), 
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE, 
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE 
); 
 
-- Insert default roles 
INSERT INTO `roles` (`name`, `description`) VALUES 
('admin', 'Full system access'), 
('security_manager', 'Security management access'), 
('assessor', 'Can perform assessments'), 
('auditor', 'Read-only access for audits'), 
('user', 'Basic user access'); 
 
-- Insert default permissions 
INSERT INTO `permissions` (`name`, `module`, `description`) VALUES 
('users.view', 'users', 'View users'), 
('users.create', 'users', 'Create users'), 
('users.edit', 'users', 'Edit users'), 
('users.delete', 'users', 'Delete users'), 
('assessments.view', 'assessments', 'View assessments'), 
('assessments.create', 'assessments', 'Create assessments'), 
('assessments.approve', 'assessments', 'Approve assessments'), 
('reports.view', 'reports', 'View reports'), 
('reports.generate', 'reports', 'Generate reports'), 
('settings.manage', 'settings', 'Manage system settings'); 
 
-- Insert default admin user (password: Admin@123) 
INSERT INTO `users` (`username`, `email`, `password`, `fullname`, `role_id`, `status`) VALUES 
('admin', 'admin@scmanagement.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 1, 'active'); 
