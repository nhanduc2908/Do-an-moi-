/**
 * Dashboard JavaScript
 */

// Dashboard initialization
document.addEventListener('DOMContentLoaded', function() {
    initDashboardCharts();
    initDashboardStats();
    initActivityFeed();
    initRealTimeUpdates();
});

// Initialize charts
function initDashboardCharts() {
    // Security Score Chart
    const securityScoreCtx = document.getElementById('securityScoreChart');
    if (securityScoreCtx) {
        new Chart(securityScoreCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Security Score',
                    data: [65, 70, 68, 72, 75, 78],
                    borderColor: '#1a56db',
                    backgroundColor: 'rgba(26,86,219,0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }
    
    // Risk Distribution Chart
    const riskCtx = document.getElementById('riskDistributionChart');
    if (riskCtx) {
        new Chart(riskCtx, {
            type: 'doughnut',
            data: {
                labels: ['Critical', 'High', 'Medium', 'Low'],
                datasets: [{
                    data: [12, 25, 35, 28],
                    backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
    
    // Vulnerability Trends Chart
    const vulnCtx = document.getElementById('vulnerabilityTrendChart');
    if (vulnCtx) {
        new Chart(vulnCtx, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [
                    {
                        label: 'Critical',
                        data: [5, 3, 2, 1],
                        backgroundColor: '#ef4444'
                    },
                    {
                        label: 'High',
                        data: [12, 10, 8, 6],
                        backgroundColor: '#f59e0b'
                    },
                    {
                        label: 'Medium',
                        data: [20, 18, 15, 12],
                        backgroundColor: '#3b82f6'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true },
                    y: { stacked: true }
                }
            }
        });
    }
}

// Dashboard statistics
function initDashboardStats() {
    // Auto-refresh stats every 30 seconds
    setInterval(() => {
        refreshDashboardStats();
    }, 30000);
}

async function refreshDashboardStats() {
    try {
        const response = await fetch('/api/dashboard/stats');
        const data = await response.json();
        
        updateStatsUI(data);
    } catch (error) {
        console.error('Failed to refresh stats:', error);
    }
}

function updateStatsUI(data) {
    const stats = ['totalUsers', 'openIncidents', 'vulnerabilities', 'complianceScore'];
    stats.forEach(stat => {
        const element = document.getElementById(stat);
        if (element && data[stat] !== undefined) {
            element.textContent = data[stat];
        }
    });
}

// Activity feed
function initActivityFeed() {
    loadRecentActivities();
    
    // Refresh every 60 seconds
    setInterval(() => {
        loadRecentActivities();
    }, 60000);
}

async function loadRecentActivities() {
    try {
        const response = await fetch('/api/dashboard/recent-activities');
        const data = await response.json();
        
        updateActivityFeed(data);
    } catch (error) {
        console.error('Failed to load activities:', error);
    }
}

function updateActivityFeed(activities) {
    const container = document.getElementById('activityFeed');
    if (!container) return;
    
    if (!activities || activities.length === 0) {
        container.innerHTML = '<div class="text-center">No recent activities</div>';
        return;
    }
    
    container.innerHTML = activities.map(activity => `
        <div class="timeline-item">
            <div class="timeline-time">${formatDate(activity.time, 'HH:mm')}</div>
            <div class="timeline-title">${activity.title}</div>
            <div class="timeline-description">${activity.description}</div>
        </div>
    `).join('');
}

// Real-time updates via WebSocket
function initRealTimeUpdates() {
    const ws = new WebSocket('ws://' + window.location.host + '/ws');
    
    ws.onmessage = function(event) {
        const data = JSON.parse(event.data);
        
        switch (data.type) {
            case 'new_incident':
                showToast(`New incident: ${data.title}`, 'warning');
                refreshDashboardStats();
                break;
            case 'score_update':
                updateSecurityScore(data.score);
                break;
            case 'alert':
                showToast(data.message, data.severity);
                break;
        }
    };
    
    ws.onerror = function(error) {
        console.error('WebSocket error:', error);
    };
}

function updateSecurityScore(score) {
    const scoreElement = document.getElementById('securityScore');
    if (scoreElement) {
        scoreElement.textContent = score;
    }
}