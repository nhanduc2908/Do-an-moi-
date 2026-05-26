/**
 * Export JavaScript - Xuất dữ liệu
 */

class ExportManager {
    constructor() {
        this.formats = ['pdf', 'excel', 'csv', 'json'];
        this.init();
    }
    
    init() {
        this.setupExportButtons();
    }
    
    setupExportButtons() {
        document.querySelectorAll('[data-export]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const type = btn.dataset.export;
                const dataType = btn.dataset.exportType || 'table';
                const fileName = btn.dataset.filename || `export_${new Date().toISOString()}`;
                
                this.export(type, dataType, fileName);
            });
        });
    }
    
    async export(format, dataType, fileName) {
        showLoading();
        
        try {
            let data;
            
            switch (dataType) {
                case 'table':
                    data = this.getTableData();
                    break;
                case 'chart':
                    data = this.getChartData();
                    break;
                case 'report':
                    data = await this.getReportData();
                    break;
                default:
                    data = this.getCurrentData();
            }
            
            let blob;
            let mimeType;
            
            switch (format) {
                case 'pdf':
                    blob = await this.exportToPdf(data, fileName);
                    mimeType = 'application/pdf';
                    break;
                case 'excel':
                    blob = this.exportToExcel(data);
                    mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    break;
                case 'csv':
                    blob = this.exportToCsv(data);
                    mimeType = 'text/csv';
                    break;
                case 'json':
                    blob = this.exportToJson(data);
                    mimeType = 'application/json';
                    break;
                default:
                    throw new Error(`Unsupported format: ${format}`);
            }
            
            this.download(blob, `${fileName}.${format}`, mimeType);
            showToast(`Export as ${format.toUpperCase()} completed`, 'success');
            
        } catch (error) {
            console.error('Export error:', error);
            showToast('Export failed', 'danger');
        } finally {
            hideLoading();
        }
    }
    
    getTableData() {
        const table = document.querySelector('.data-table');
        if (!table) return [];
        
        const headers = [];
        const data = [];
        
        // Get headers
        table.querySelectorAll('thead th').forEach(th => {
            headers.push(th.textContent.trim());
        });
        
        // Get rows
        table.querySelectorAll('tbody tr').forEach(row => {
            const rowData = {};
            row.querySelectorAll('td').forEach((td, index) => {
                rowData[headers[index] || `col_${index}`] = td.textContent.trim();
            });
            data.push(rowData);
        });
        
        return { headers, data };
    }
    
    getChartData() {
        const charts = document.querySelectorAll('.chart-container canvas');
        const chartData = [];
        
        charts.forEach((chart, index) => {
            const chartInstance = Chart.getChart(chart);
            if (chartInstance) {
                chartData.push({
                    name: `Chart ${index + 1}`,
                    type: chartInstance.config.type,
                    data: chartInstance.data
                });
            }
        });
        
        return chartData;
    }
    
    async getReportData() {
        const reportId = document.querySelector('[data-report-id]')?.dataset.reportId;
        if (reportId) {
            const response = await fetch(`/api/reports/${reportId}/data`);
            return await response.json();
        }
        return {};
    }
    
    getCurrentData() {
        // Get current view data
        return {
            url: window.location.href,
            title: document.title,
            timestamp: new Date().toISOString(),
            data: document.querySelector('#app-data')?.dataset || {}
        };
    }
    
    async exportToPdf(data, fileName) {
        // Use html2pdf or similar library
        const element = document.querySelector('.export-content') || document.body;
        
        const opt = {
            margin: [0.5, 0.5, 0.5, 0.5],
            filename: `${fileName}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        
        return new Promise((resolve) => {
            html2pdf().set(opt).from(element).outputPdf().then(resolve);
        });
    }
    
    exportToExcel(data) {
        const worksheet = XLSX.utils.json_to_sheet(data.data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Data');
        
        const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
        return new Blob([excelBuffer], { type: 'application/octet-stream' });
    }
    
    exportToCsv(data) {
        const headers = data.headers || Object.keys(data.data[0] || {});
        const rows = data.data.map(row => 
            headers.map(header => JSON.stringify(row[header] || '')).join(',')
        );
        
        const csv = [headers.join(','), ...rows].join('\n');
        return new Blob(["\uFEFF" + csv], { type: 'text/csv;charset=utf-8;' });
    }
    
    exportToJson(data) {
        const json = JSON.stringify(data, null, 2);
        return new Blob([json], { type: 'application/json' });
    }
    
    download(blob, fileName, mimeType) {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
}

// Initialize export manager
const exportManager = new ExportManager();

window.ExportManager = exportManager;