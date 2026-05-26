/**
 * Import JavaScript - Nhập dữ liệu
 */

class ImportManager {
    constructor() {
        this.supportedTypes = ['csv', 'json', 'xlsx', 'xml'];
        this.init();
    }
    
    init() {
        this.setupImportButtons();
        this.setupDropZone();
    }
    
    setupImportButtons() {
        document.querySelectorAll('[data-import]').forEach(btn => {
            btn.addEventListener('click', () => {
                this.showImportModal(btn.dataset.importType || 'data');
            });
        });
    }
    
    setupDropZone() {
        const dropZone = document.getElementById('dropZone');
        if (!dropZone) return;
        
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
        
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });
        
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.processFile(files[0]);
            }
        });
        
        const fileInput = document.getElementById('fileInput');
        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    this.processFile(e.target.files[0]);
                }
            });
        }
    }
    
    showImportModal(type) {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Import ${type.charAt(0).toUpperCase() + type.slice(1)}</h3>
                    <button class="close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="import-options">
                        <div class="drop-zone" id="dropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop file here or click to browse</p>
                            <input type="file" id="fileInput" accept=".csv,.json,.xlsx,.xml" style="display: none;">
                            <button class="btn btn-secondary browse-btn">Browse Files</button>
                        </div>
                        <div class="import-preview" style="display: none;">
                            <h4>Preview</h4>
                            <div class="preview-table"></div>
                        </div>
                        <div class="import-mapping" style="display: none;">
                            <h4>Field Mapping</h4>
                            <div class="mapping-fields"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary cancel-btn">Cancel</button>
                    <button class="btn btn-primary import-btn" disabled>Import</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        const closeBtn = modal.querySelector('.close');
        const cancelBtn = modal.querySelector('.cancel-btn');
        const browseBtn = modal.querySelector('.browse-btn');
        const fileInput = modal.querySelector('#fileInput');
        const importBtn = modal.querySelector('.import-btn');
        
        closeBtn?.addEventListener('click', () => modal.remove());
        cancelBtn?.addEventListener('click', () => modal.remove());
        
        browseBtn?.addEventListener('click', () => {
            fileInput.click();
        });
        
        fileInput?.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.processFile(e.target.files[0], modal, importBtn);
            }
        });
        
        this.setupDropZoneInModal(modal, importBtn);
    }
    
    setupDropZoneInModal(modal, importBtn) {
        const dropZone = modal.querySelector('#dropZone');
        if (!dropZone) return;
        
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
        
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });
        
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.processFile(files[0], modal, importBtn);
            }
        });
    }
    
    async processFile(file, modal, importBtn) {
        if (!this.isSupported(file.name)) {
            showToast(`Unsupported file type. Supported: ${this.supportedTypes.join(', ')}`, 'danger');
            return;
        }
        
        showLoading();
        
        try {
            const data = await this.readFile(file);
            const preview = await this.previewData(data, file.name);
            
            this.showPreview(modal, preview);
            if (importBtn) importBtn.disabled = false;
            
            importBtn.onclick = () => this.importData(data, file.name, modal);
            
        } catch (error) {
            console.error('File processing error:', error);
            showToast('Failed to process file', 'danger');
        } finally {
            hideLoading();
        }
    }
    
    isSupported(fileName) {
        const extension = fileName.split('.').pop().toLowerCase();
        return this.supportedTypes.includes(extension);
    }
    
    readFile(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                try {
                    const extension = file.name.split('.').pop().toLowerCase();
                    let data;
                    
                    switch (extension) {
                        case 'csv':
                            data = this.parseCSV(e.target.result);
                            break;
                        case 'json':
                            data = JSON.parse(e.target.result);
                            break;
                        case 'xlsx':
                            data = this.parseExcel(e.target.result);
                            break;
                        default:
                            reject(new Error('Unsupported format'));
                    }
                    
                    resolve({ data, fileName: file.name, type: extension });
                } catch (error) {
                    reject(error);
                }
            };
            
            reader.onerror = reject;
            reader.readAsText(file);
        });
    }
    
    parseCSV(csvText) {
        const lines = csvText.split('\n');
        const headers = lines[0].split(',').map(h => h.trim());
        const data = [];
        
        for (let i = 1; i < lines.length; i++) {
            if (lines[i].trim()) {
                const values = lines[i].split(',').map(v => v.trim());
                const row = {};
                headers.forEach((header, index) => {
                    row[header] = values[index] || '';
                });
                data.push(row);
            }
        }
        
        return { headers, data };
    }
    
    parseExcel(data) {
        // Use SheetJS for Excel parsing
        const workbook = XLSX.read(data, { type: 'binary' });
        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];
        const jsonData = XLSX.utils.sheet_to_json(worksheet);
        
        const headers = Object.keys(jsonData[0] || {});
        return { headers, data: jsonData };
    }
    
    async previewData(fileData, fileName) {
        const sampleSize = 5;
        const previewData = fileData.data.slice(0, sampleSize);
        
        return {
            fileName: fileName,
            totalRows: fileData.data.length,
            headers: fileData.headers,
            preview: previewData
        };
    }
    
    showPreview(modal, preview) {
        const previewContainer = modal.querySelector('.import-preview');
        const previewTable = modal.querySelector('.preview-table');
        
        if (!previewContainer || !previewTable) return;
        
        let html = '<table class="data-table"><thead><tr>';
        preview.headers.forEach(header => {
            html += `<th>${this.escapeHtml(header)}</th>`;
        });
        html += '</tr></thead><tbody>';
        
        preview.preview.forEach(row => {
            html += '<tr>';
            preview.headers.forEach(header => {
                html += `<td>${this.escapeHtml(row[header] || '')}</td>`;
            });
            html += '</tr>';
        });
        
        html += '</tbody></table>';
        html += `<p class="mt-2">Total rows: ${preview.totalRows}</p>`;
        
        previewTable.innerHTML = html;
        previewContainer.style.display = 'block';
    }
    
    async importData(fileData, fileName, modal) {
        showLoading();
        
        try {
            const importType = modal.querySelector('[data-import]')?.dataset.importType || 'data';
            const response = await fetch(`/api/import/${importType}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    fileName: fileName,
                    data: fileData.data,
                    headers: fileData.headers
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(`Successfully imported ${result.imported_count} records`, 'success');
                modal.remove();
                if (result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    location.reload();
                }
            } else {
                throw new Error(result.message || 'Import failed');
            }
        } catch (error) {
            console.error('Import error:', error);
            showToast('Import failed', 'danger');
        } finally {
            hideLoading();
        }
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize import manager
const importManager = new ImportManager();

window.ImportManager = importManager;