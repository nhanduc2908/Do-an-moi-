/**
 * Admin Panel JavaScript
 */

// Admin initialization
document.addEventListener('DOMContentLoaded', function() {
    initAdminSidebar();
    initDataTables();
    initBulkActions();
    initUserManagement();
    initRoleManagement();
    initSystemSettings();
});

// Admin Sidebar
function initAdminSidebar() {
    const navLinks = document.querySelectorAll('.nav-link');
    const currentPath = window.location.pathname;
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href)) {
            link.classList.add('active');
        }
        
        link.addEventListener('click', function() {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

// Data Tables
function initDataTables() {
    const tables = document.querySelectorAll('.data-table');
    
    tables.forEach(table => {
        // Make sortable
        makeSortable(table);
        
        // Add search
        addTableSearch(table);
        
        // Add pagination
        addTablePagination(table);
    });
}

function makeSortable(table) {
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
}

function addTableSearch(table) {
    const searchInput = document.getElementById('tableSearch');
    if (!searchInput) return;
    
    searchInput.addEventListener('keyup', () => {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function addTablePagination(table) {
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
    
    createPagination(totalPages, (page) => {
        currentPage = page;
        showPage(currentPage);
    });
    
    showPage(1);
}

function createPagination(totalPages, callback) {
    const container = document.getElementById('pagination');
    if (!container || totalPages <= 1) return;
    
    let html = '';
    for (let i = 1; i <= totalPages; i++) {
        html += `<button class="pagination-item" data-page="${i}">${i}</button>`;
    }
    
    container.innerHTML = html;
    
    container.querySelectorAll('.pagination-item').forEach(btn => {
        btn.addEventListener('click', () => {
            const page = parseInt(btn.dataset.page);
            callback(page);
            
            container.querySelectorAll('.pagination-item').forEach(b => {
                b.classList.remove('active');
            });
            btn.classList.add('active');
        });
    });
    
    if (totalPages > 0) {
        container.querySelector('.pagination-item').classList.add('active');
    }
}

// Bulk Actions
function initBulkActions() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.select-item');
    const bulkActions = document.getElementById('bulkActions');
    
    if (selectAll) {
        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            toggleBulkActions(bulkActions, selectAll.checked);
        });
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const anyChecked = [...checkboxes].some(c => c.checked);
            toggleBulkActions(bulkActions, anyChecked);
        });
    });
}

function toggleBulkActions(container, show) {
    if (container) {
        container.style.display = show ? 'flex' : 'none';
    }
}

// User Management
function initUserManagement() {
    const userForm = document.getElementById('userForm');
    if (userForm) {
        userForm.addEventListener('submit', handleUserSubmit);
    }
}

async function handleUserSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    try {
        showLoading();
        const response = await fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('User saved successfully', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showValidationErrors(data.errors);
        }
    } catch (error) {
        showToast('Failed to save user', 'danger');
    } finally {
        hideLoading();
    }
}

function showValidationErrors(errors) {
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
}

// Role Management
function initRoleManagement() {
    const permissionCheckboxes = document.querySelectorAll('[data-permission]');
    
    permissionCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            updateRolePermissions();
        });
    });
}

async function updateRolePermissions() {
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
        
        if (response.ok) {
            showToast('Permissions updated', 'success');
        }
    } catch (error) {
        console.error('Failed to update permissions:', error);
    }
}

// System Settings
function initSystemSettings() {
    const settingsForm = document.getElementById('settingsForm');
    if (settingsForm) {
        settingsForm.addEventListener('submit', handleSettingsSubmit);
    }
}

async function handleSettingsSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    try {
        showLoading();
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Settings saved successfully', 'success');
        } else {
            showToast(data.message || 'Failed to save settings', 'danger');
        }
    } catch (error) {
        showToast('Failed to save settings', 'danger');
    } finally {
        hideLoading();
    }
}

// Delete confirmation
function confirmDelete(url, itemName) {
    confirmDialog(`Are you sure you want to delete ${itemName}?`, async () => {
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                showToast(`${itemName} deleted successfully`, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(`Failed to delete ${itemName}`, 'danger');
            }
        } catch (error) {
            showToast(`Failed to delete ${itemName}`, 'danger');
        }
    });
}

// Export functions
window.confirmDelete = confirmDelete;