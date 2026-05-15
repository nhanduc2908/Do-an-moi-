/**
 * Security Platform - Admin Dashboard JavaScript
 */

// Admin Module
const Admin = {
    // Initialize
    init: function() {
        this.initSidebar();
        this.initDataTable();
        this.initDeleteConfirmation();
        this.initBulkActions();
        this.initUserManagement();
        this.initRoleManagement();
    },
    
    // Sidebar Toggle
    initSidebar: function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.admin-sidebar');
        
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }
    },
    
    // Data Table
    initDataTable: function() {
        const tables = document.querySelectorAll('.data-table');
        tables.forEach(table => {
            this.makeSortable(table);
            this.makeSearchable(table);
            this.makePaginated(table);
        });
    },
    
    makeSortable: function(table) {
        const headers = table.querySelectorAll('th[data-sortable]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                const column = header.cellIndex;
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                const isAsc = header.classList.contains('asc');
                
                rows.sort((a, b) => {
                    const aValue = a.cells[column].textContent;
                    const bValue = b.cells[column].textContent;
                    return isAsc ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                });
                
                rows.forEach(row => table.querySelector('tbody').appendChild(row));
                header.classList.toggle('asc', !isAsc);
                header.classList.toggle('desc', isAsc);
            });
        });
    },
    
    makeSearchable: function(table) {
        const searchInput = document.getElementById('tableSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', () => {
                const searchTerm = searchInput.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    },
    
    makePaginated: function(table) {
        const rows = table.querySelectorAll('tbody tr');
        const rowsPerPage = 10;
        let currentPage = 1;
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        
        const showPage = (page) => {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            
            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
        };
        
        this.createPagination(totalPages, (page) => {
            currentPage = page;
            showPage(currentPage);
        });
        
        showPage(1);
    },
    
    createPagination: function(totalPages, callback) {
        const paginationContainer = document.getElementById('pagination');
        if (!paginationContainer || totalPages <= 1) return;
        
        let paginationHtml = '';
        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `<button class="pagination-item" data-page="${i}">${i}</button>`;
        }
        
        paginationContainer.innerHTML = paginationHtml;
        
        paginationContainer.querySelectorAll('.pagination-item').forEach(btn => {
            btn.addEventListener('click', () => {
                const page = parseInt(btn.dataset.page);
                callback(page);
                
                paginationContainer.querySelectorAll('.pagination-item').forEach(b => {
                    b.classList.remove('active');
                });
                btn.classList.add('active');
            });
        });
        
        if (totalPages > 0) {
            paginationContainer.querySelector('.pagination-item').classList.add('active');
        }
    },
    
    // Delete Confirmation
    initDeleteConfirmation: function() {
        const deleteButtons = document.querySelectorAll('[data-delete]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const itemName = button.dataset.item || 'this item';
                
                if (confirm(`Are you sure you want to delete ${itemName}? This action cannot be undone.`)) {
                    const url = button.dataset.url;
                    this.deleteItem(url);
                }
            });
        });
    },
    
    deleteItem: async function(url) {
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to delete item');
            }
        } catch (error) {
            console.error('Delete failed:', error);
            alert('An error occurred');
        }
    },
    
    // Bulk Actions
    initBulkActions: function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.select-item');
        const bulkActions = document.getElementById('bulkActions');
        
        if (selectAll) {
            selectAll.addEventListener('change', () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                this.toggleBulkActions(bulkActions, selectAll.checked || [...checkboxes].some(cb => cb.checked));
            });
        }
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const anyChecked = [...checkboxes].some(c => c.checked);
                this.toggleBulkActions(bulkActions, anyChecked);
            });
        });
    },
    
    toggleBulkActions: function(container, show) {
        if (container) {
            container.style.display = show ? 'flex' : 'none';
        }
    },
    
    // User Management
    initUserManagement: function() {
        const userForm = document.getElementById('userForm');
        if (userForm) {
            userForm.addEventListener('submit', this.handleUserSubmit.bind(this));
        }
    },
    
    handleUserSubmit: async function(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                location.href = '/admin/users';
            } else {
                const errors = await response.json();
                this.showValidationErrors(errors);
            }
        } catch (error) {
            console.error('User submission failed:', error);
        }
    },
    
    showValidationErrors: function(errors) {
        // Clear existing errors
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        // Show new errors
        for (const [field, messages] of Object.entries(errors)) {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = messages.join(', ');
                input.parentNode.appendChild(errorDiv);
            }
        }
    },
    
    // Role Management
    initRoleManagement: function() {
        const permissionCheckboxes = document.querySelectorAll('[data-permission]');
        permissionCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                this.updateRolePermissions();
            });
        });
    },
    
    updateRolePermissions: async function() {
        const roleId = document.getElementById('roleId')?.value;
        if (!roleId) return;
        
        const permissions = [];
        document.querySelectorAll('[data-permission]:checked').forEach(cb => {
            permissions.push(cb.value);
        });
        
        try {
            const response = await fetch(`/admin/roles/${roleId}/permissions`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ permissions })
            });
            
            if (!response.ok) {
                console.error('Failed to update permissions');
            }
        } catch (error) {
            console.error('Permission update failed:', error);
        }
    },
    
    // Chart Initialization
    initAdminCharts: function() {
        // Users Activity Chart
        const userActivityCanvas = document.getElementById('userActivityChart');
        if (userActivityCanvas) {
            new Chart(userActivityCanvas, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Active Users',
                        data: [45, 52, 48, 60, 55, 32, 28],
                        borderColor: '#2563eb',
                        fill: true
                    }]
                }
            });
        }
        
        // System Health Chart
        const systemHealthCanvas = document.getElementById('systemHealthChart');
        if (systemHealthCanvas) {
            new Chart(systemHealthCanvas, {
                type: 'gauge',
                data: {
                    datasets: [{
                        value: 85,
                        minValue: 0,
                        maxValue: 100,
                        backgroundColor: '#10b981'
                    }]
                }
            });
        }
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    Admin.init();
    Admin.initAdminCharts();
});