/**
 * Security Platform - Main Application JavaScript
 */

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initSecurityScoreChart();
    initNotifications();
    initFormValidation();
    initTooltips();
});

// Security Score Chart
function initSecurityScoreChart() {
    const canvas = document.getElementById('securityScoreChart');
    if (!canvas) return;
    
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Security Score',
                data: [65, 70, 68, 75],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Score: ${context.raw}`;
                        }
                    }
                }
            }
        }
    });
}

// Risk Distribution Chart
function initRiskChart() {
    const canvas = document.getElementById('riskChart');
    if (!canvas) return;
    
    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: ['Critical', 'High', 'Medium', 'Low'],
            datasets: [{
                data: [12, 25, 35, 28],
                backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Vulnerability Trends Chart
function initVulnerabilityChart() {
    const canvas = document.getElementById('vulnerabilityChart');
    if (!canvas) return;
    
    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Critical',
                    data: [5, 3, 2, 4, 1, 0],
                    backgroundColor: '#ef4444'
                },
                {
                    label: 'High',
                    data: [12, 10, 8, 6, 5, 4],
                    backgroundColor: '#f59e0b'
                },
                {
                    label: 'Medium',
                    data: [20, 18, 15, 12, 10, 8],
                    backgroundColor: '#3b82f6'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    stacked: true
                },
                x: {
                    stacked: true
                }
            }
        }
    });
}

// Notifications System
function initNotifications() {
    const notificationBtn = document.getElementById('notificationBtn');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', toggleNotifications);
    }
}

function toggleNotifications() {
    const panel = document.getElementById('notificationPanel');
    if (panel) {
        panel.classList.toggle('show');
    }
}

function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <span class="toast-message">${message}</span>
            <button class="toast-close">&times;</button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
    
    toast.querySelector('.toast-close').addEventListener('click', () => {
        toast.remove();
    });
}

// Form Validation
function initFormValidation() {
    const forms = document.querySelectorAll('[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', validateForm);
    });
}

function validateForm(event) {
    const form = event.target;
    const inputs = form.querySelectorAll('[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showInputError(input, 'This field is required');
            isValid = false;
        } else if (input.type === 'email' && !isValidEmail(input.value)) {
            showInputError(input, 'Please enter a valid email address');
            isValid = false;
        } else if (input.minLength && input.value.length < input.minLength) {
            showInputError(input, `Minimum length is ${input.minLength} characters`);
            isValid = false;
        }
    });
    
    if (!isValid) {
        event.preventDefault();
    }
}

function showInputError(input, message) {
    input.classList.add('is-invalid');
    const error = document.createElement('div');
    error.className = 'invalid-feedback';
    error.textContent = message;
    input.parentNode.appendChild(error);
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// Tooltips
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(event) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = event.target.dataset.tooltip;
    document.body.appendChild(tooltip);
    
    const rect = event.target.getBoundingClientRect();
    tooltip.style.top = `${rect.top - tooltip.offsetHeight - 5}px`;
    tooltip.style.left = `${rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)}px`;
    tooltip.classList.add('show');
}

function hideTooltip() {
    const tooltip = document.querySelector('.tooltip');
    if (tooltip) tooltip.remove();
}

// AJAX Helper
const api = {
    get: async (url) => {
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        return response.json();
    },
    
    post: async (url, data) => {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        return response.json();
    }
};

// Auto-refresh data
function autoRefresh(interval = 30000) {
    setInterval(() => {
        refreshDashboardData();
    }, interval);
}

async function refreshDashboardData() {
    try {
        const data = await api.get('/api/dashboard/stats');
        updateDashboardUI(data);
    } catch (error) {
        console.error('Failed to refresh dashboard:', error);
    }
}

function updateDashboardUI(data) {
    // Update UI with fresh data
    if (data.securityScore) {
        document.getElementById('securityScore').textContent = data.securityScore;
    }
}

// Export functions for use in other scripts
window.SecurityApp = {
    showNotification,
    api,
    initSecurityScoreChart,
    initRiskChart,
    initVulnerabilityChart
};