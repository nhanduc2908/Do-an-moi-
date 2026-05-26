/**
 * DataTable JavaScript - Quản lý bảng dữ liệu
 */

class DataTable {
    constructor(element, options = {}) {
        this.table = typeof element === 'string' ? document.querySelector(element) : element;
        this.options = {
            pageSize: options.pageSize || 10,
            sortable: options.sortable !== false,
            searchable: options.searchable !== false,
            pagination: options.pagination !== false,
            ...options
        };
        
        this.currentPage = 1;
        this.sortColumn = null;
        this.sortOrder = 'asc';
        this.searchTerm = '';
        this.data = [];
        this.filteredData = [];
        
        this.init();
    }
    
    init() {
        this.extractData();
        this.render();
        this.attachEvents();
    }
    
    extractData() {
        const headers = [];
        const rows = [];
        
        // Extract headers
        this.table.querySelectorAll('thead th').forEach(th => {
            headers.push({
                field: th.getAttribute('data-field') || th.textContent.toLowerCase().replace(/\s+/g, '_'),
                label: th.textContent,
                sortable: th.hasAttribute('data-sortable') || this.options.sortable
            });
        });
        
        // Extract data
        this.table.querySelectorAll('tbody tr').forEach(row => {
            const rowData = {};
            row.querySelectorAll('td').forEach((td, index) => {
                const field = headers[index]?.field || `col_${index}`;
                rowData[field] = td.textContent;
                rowData[`${field}_html`] = td.innerHTML;
            });
            rows.push(rowData);
        });
        
        this.headers = headers;
        this.data = rows;
        this.filteredData = [...rows];
    }
    
    render() {
        this.renderHeader();
        this.renderBody();
        if (this.options.pagination) {
            this.renderPagination();
        }
    }
    
    renderHeader() {
        const thead = this.table.querySelector('thead');
        if (!thead) return;
        
        thead.innerHTML = `
            <tr>
                ${this.headers.map(header => `
                    <th data-field="${header.field}" ${header.sortable ? 'data-sortable="true"' : ''}>
                        ${header.label}
                        ${header.sortable ? '<span class="sort-icon"></span>' : ''}
                    </th>
                `).join('')}
            </tr>
        `;
    }
    
    renderBody() {
        const tbody = this.table.querySelector('tbody');
        if (!tbody) return;
        
        const start = (this.currentPage - 1) * this.options.pageSize;
        const end = start + this.options.pageSize;
        const pageData = this.filteredData.slice(start, end);
        
        tbody.innerHTML = pageData.map(row => `
            <tr>
                ${this.headers.map(header => `
                    <td data-label="${header.label}">
                        ${row[`${header.field}_html`] || row[header.field] || ''}
                    </td>
                `).join('')}
            </tr>
        `).join('');
    }
    
    renderPagination() {
        const totalPages = Math.ceil(this.filteredData.length / this.options.pageSize);
        const container = document.getElementById(`${this.table.id}_pagination`) || this.createPaginationContainer();
        
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let html = '<div class="pagination">';
        
        // Previous button
        html += `<button class="pagination-prev" ${this.currentPage === 1 ? 'disabled' : ''}>&laquo;</button>`;
        
        // Page numbers
        const startPage = Math.max(1, this.currentPage - 2);
        const endPage = Math.min(totalPages, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            html += `<button class="pagination-item ${i === this.currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }
        
        // Next button
        html += `<button class="pagination-next" ${this.currentPage === totalPages ? 'disabled' : ''}>&raquo;</button>`;
        html += '</div>';
        
        container.innerHTML = html;
        
        // Attach events
        container.querySelectorAll('.pagination-item').forEach(btn => {
            btn.addEventListener('click', () => {
                this.goToPage(parseInt(btn.dataset.page));
            });
        });
        
        container.querySelector('.pagination-prev')?.addEventListener('click', () => {
            if (this.currentPage > 1) this.goToPage(this.currentPage - 1);
        });
        
        container.querySelector('.pagination-next')?.addEventListener('click', () => {
            if (this.currentPage < totalPages) this.goToPage(this.currentPage + 1);
        });
    }
    
    createPaginationContainer() {
        const container = document.createElement('div');
        container.id = `${this.table.id}_pagination`;
        container.className = 'pagination-container';
        this.table.parentNode.appendChild(container);
        return container;
    }
    
    attachEvents() {
        // Sorting
        if (this.options.sortable) {
            this.table.querySelectorAll('th[data-sortable]').forEach(th => {
                th.addEventListener('click', () => {
                    const field = th.dataset.field;
                    this.sort(field);
                });
            });
        }
        
        // Search
        if (this.options.searchable) {
            const searchInput = document.getElementById(`${this.table.id}_search`);
            if (searchInput) {
                searchInput.addEventListener('keyup', () => {
                    this.search(searchInput.value);
                });
            }
        }
    }
    
    sort(field) {
        if (this.sortColumn === field) {
            this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = field;
            this.sortOrder = 'asc';
        }
        
        this.filteredData.sort((a, b) => {
            let aVal = a[field] || '';
            let bVal = b[field] || '';
            
            if (this.sortOrder === 'asc') {
                return aVal.localeCompare(bVal);
            } else {
                return bVal.localeCompare(aVal);
            }
        });
        
        this.currentPage = 1;
        this.render();
        this.updateSortIcons();
    }
    
    search(term) {
        this.searchTerm = term.toLowerCase();
        
        if (!this.searchTerm) {
            this.filteredData = [...this.data];
        } else {
            this.filteredData = this.data.filter(row => {
                return Object.values(row).some(value => 
                    String(value).toLowerCase().includes(this.searchTerm)
                );
            });
        }
        
        this.currentPage = 1;
        this.render();
    }
    
    goToPage(page) {
        this.currentPage = page;
        this.render();
    }
    
    updateSortIcons() {
        this.table.querySelectorAll('th .sort-icon').forEach(icon => {
            icon.textContent = '';
        });
        
        if (this.sortColumn) {
            const th = this.table.querySelector(`th[data-field="${this.sortColumn}"]`);
            const icon = th?.querySelector('.sort-icon');
            if (icon) {
                icon.textContent = this.sortOrder === 'asc' ? '↑' : '↓';
            }
        }
    }
    
    refresh(data) {
        if (data) {
            this.data = data;
            this.filteredData = [...data];
        }
        this.currentPage = 1;
        this.render();
    }
}

// Initialize DataTables
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.data-table').forEach(table => {
        if (!table.dataset.initialized) {
            new DataTable(table, {
                pageSize: parseInt(table.dataset.pageSize) || 10,
                sortable: table.dataset.sortable !== 'false',
                searchable: table.dataset.searchable !== 'false',
                pagination: table.dataset.pagination !== 'false'
            });
            table.dataset.initialized = 'true';
        }
    });
});

window.DataTable = DataTable;