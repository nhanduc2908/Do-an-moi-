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
    <title>Dashboard - SC Management System</title> 
    <link rel="stylesheet" href="../../assets/css/vendors/bootstrap.min.css"> 
    <link rel="stylesheet" href="../../assets/css/vendors/fontawesome.min.css"> 
    <link rel="stylesheet" href="../../assets/css/vendors/fullcalendar.min.css"> 
    <link rel="stylesheet" href="../../assets/css/main.css"> 
    <link rel="stylesheet" href="../../assets/css/dashboard.css"> 
</head> 
<body> 
    <?php include '../../includes/header.php'; ?> 
    <?php include '../../includes/sidebar.php'; ?> 
    <div class="main-content"> 
        <div class="page-header"> 
            <h1>Dashboard</h1> 
            <div class="header-actions"> 
                <span class="last-updated">Last updated: <?php echo date('Y-m-d H:i:s'); ?></span> 
            </div> 
        </div> 
 
        <!-- Stats Cards --
        <div class="stats-grid"> 
            <div class="stat-card"> 
                <div class="stat-icon">??</div> 
                <div class="stat-info"> 
                    <h3>Security Score</h3> 
                    <div class="stat-value" id="securityScore">85</div> 
                    <div class="stat-trend up"> 5% from last month</div> 
                </div> 
            </div> 
            <div class="stat-card"> 
                <div class="stat-icon">??</div> 
                <div class="stat-info"> 
                    <h3>Open Incidents</h3> 
                    <div class="stat-value" id="openIncidents">12</div> 
                    <div class="stat-trend down"> 3 from last week</div> 
                </div> 
            </div> 
            <div class="stat-card"> 
                <div class="stat-icon">??</div> 
                <div class="stat-info"> 
                    <h3>Vulnerabilities</h3> 
                    <div class="stat-value" id="vulnerabilities">47</div> 
                    <div class="stat-trend critical">8 Critical</div> 
                </div> 
            </div> 
            <div class="stat-card"> 
                <div class="stat-icon">?</div> 
                <div class="stat-info"> 
                    <h3>Compliance</h3> 
                    <div class="stat-value">92%</div> 
                    <div class="stat-trend up"> 2% this quarter</div> 
                </div> 
            </div> 
        </div> 
 
        <!-- Charts Row --
        <div class="charts-row"> 
            <div class="chart-container"> 
                <h3>Security Trends</h3> 
                <canvas id="securityTrendsChart"></canvas> 
            </div> 
            <div class="chart-container"> 
                <h3>Risk Distribution</h3> 
                <canvas id="riskDistributionChart"></canvas> 
            </div> 
        </div> 
 
        <!-- Recent Activity --
        <div class="recent-activity"> 
            <h3>Recent Activity</h3> 
            <table class="activity-table"> 
                <thead> 
                    <tr> 
                        <th>Time</th> 
                        <th>User</th> 
                        <th>Action</th> 
                        <th>Status</th> 
                    </tr> 
                </thead> 
                <tbody id="activityLog"> 
                    <?php 
                    $stmt = $pdo-
                    while($row = $stmt- { 
                        echo "^^<tr^^>"; 
                        echo "^^<td^^>" . $row['created_at'] . "^^</td^^>"; 
                        echo "^^<td^^>" . htmlspecialchars($row['username']) . "^^</td^^>"; 
                        echo "^^<td^^>" . htmlspecialchars($row['action']) . "^^</td^^>"; 
                        echo "^^<td^^>^^<span class='status-badge " . $row['status'] . "'^^>" . $row['status'] . "^^</span^^>^^</td^^>"; 
                        echo "^^</tr^^>"; 
                    } 
                    ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
    <?php include '../../includes/footer.php'; ?> 
    <script src="../../assets/js/vendors/chartjs.min.js"></script> 
    <script src="../../assets/js/dashboard.js"></script> 
</body> 
