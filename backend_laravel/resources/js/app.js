import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
Alpine.start();

// Security Dashboard
document.addEventListener('DOMContentLoaded', function() {
    initSecurityCharts();
    initRealTimeAlerts();
    initDataTables();
});

function initSecurityCharts() {
    const scoreChart = document.getElementById('securityScoreChart');
    if (scoreChart) {
        new Chart(scoreChart, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Security Score',
                    data: [65, 72, 68, 78],
                    borderColor: '#1a56db',
                    backgroundColor: 'rgba(26,86,219,0.1)',
                    tension: 0.4,
                    fill: true
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
}

function initRealTimeAlerts() {
    Echo.channel('security-alerts')
        .listen('AlertTriggered', (event) => {
            showNotification(event.alert);
            updateAlertCounter();
        });
}

function showNotification(alert) {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        alert.severity === 'critical' ? 'bg-red-500' : 'bg-yellow-500'
    } text-white`;
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <span class="font-bold">${alert.title}</span>
            <span>${alert.message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4">×</button>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

window.showNotification = showNotification;