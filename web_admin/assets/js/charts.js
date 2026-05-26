/**
 * Charts JavaScript - Biểu đồ và thống kê
 */

class ChartManager {
    constructor() {
        this.charts = {};
    }
    
    createLineChart(elementId, data, options = {}) {
        const ctx = document.getElementById(elementId);
        if (!ctx) return null;
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                },
                ...options
            }
        });
        
        this.charts[elementId] = chart;
        return chart;
    }
    
    createBarChart(elementId, data, options = {}) {
        const ctx = document.getElementById(elementId);
        if (!ctx) return null;
        
        const chart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                ...options
            }
        });
        
        this.charts[elementId] = chart;
        return chart;
    }
    
    createPieChart(elementId, data, options = {}) {
        const ctx = document.getElementById(elementId);
        if (!ctx) return null;
        
        const chart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                ...options
            }
        });
        
        this.charts[elementId] = chart;
        return chart;
    }
    
    createDoughnutChart(elementId, data, options = {}) {
        const ctx = document.getElementById(elementId);
        if (!ctx) return null;
        
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom' }
                },
                ...options
            }
        });
        
        this.charts[elementId] = chart;
        return chart;
    }
    
    updateChart(chartId, data) {
        const chart = this.charts[chartId];
        if (chart) {
            chart.data = data;
            chart.update();
        }
    }
    
    destroyChart(chartId) {
        const chart = this.charts[chartId];
        if (chart) {
            chart.destroy();
            delete this.charts[chartId];
        }
    }
}

// Initialize charts
const chartManager = new ChartManager();

document.addEventListener('DOMContentLoaded', () => {
    // Security Score Chart
    const securityScoreData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Security Score',
            data: [65, 70, 68, 72, 75, 78],
            borderColor: '#1a56db',
            backgroundColor: 'rgba(26,86,219,0.1)',
            fill: true,
            tension: 0.4
        }]
    };
    chartManager.createLineChart('securityScoreChart', securityScoreData);
    
    // Risk Distribution Chart
    const riskData = {
        labels: ['Critical', 'High', 'Medium', 'Low'],
        datasets: [{
            data: [12, 25, 35, 28],
            backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981']
        }]
    };
    chartManager.createDoughnutChart('riskChart', riskData);
    
    // Vulnerability Trends
    const vulnData = {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [
            { label: 'Critical', data: [5, 3, 2, 1], backgroundColor: '#ef4444' },
            { label: 'High', data: [12, 10, 8, 6], backgroundColor: '#f59e0b' },
            { label: 'Medium', data: [20, 18, 15, 12], backgroundColor: '#3b82f6' }
        ]
    };
    chartManager.createBarChart('vulnerabilityChart', vulnData, { scales: { x: { stacked: true }, y: { stacked: true } } });
});

window.ChartManager = chartManager;