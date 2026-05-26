<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
requireRole('admin'); // Only admins

$page = $_GET['page'] ?? 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$roleFilter = $_GET['role'] ?? '';

$query = "SELECT * FROM users WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (username LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($roleFilter) {
    $query .= " AND role = ?";
    $params[] = $roleFilter;
}

$query .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();

// Render table...
?>