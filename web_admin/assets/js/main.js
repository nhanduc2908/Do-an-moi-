/**
 * Main JavaScript for Security Admin Portal
 */

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    initDropdowns();
    initModals();
    initTooltips();
    initNotifications();
});

// Sidebar Toggle
function initSidebar() {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
}

// Dropdowns
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (trigger && menu) {
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('show');
            });
        }
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
        });
    });
}

// Modals
function initModals() {
    const modals = document.querySelectorAll('.modal');
    const closeBtns = document.querySelectorAll('.modal-close, .modal .close');
    
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal');
            if (modal) closeModal(modal);
        });
    });
    
    // Close modal when clicking outside
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal);
            }
        });
    });
}

function openModal(modal) {
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modal) {
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Tooltips
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(e) {
    const target = e.target;
    const text = target.getAttribute('data-tooltip');
    
    if (!text) return;
    
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip-text';
    tooltip.textContent = text;
    tooltip.style.position = 'absolute';
    
    target.style.position = 'relative';
    target.appendChild(tooltip);
}

function hideTooltip(e) {
    const tooltip = e.target.querySelector('.tooltip-text');
    if (tooltip) tooltip.remove();
}

// Notifications
function initNotifications() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
}

// Show Toast Notification
function showToast(message, type = 'info', title = '') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const icons = {
        success: '✓',
        danger: '✗',
        warning: '⚠',
        info: 'ℹ'
    };
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-icon">${icons[type] || icons.info}</div>
        <div class="toast-content">
            ${title ? `<div class="toast-title">${title}</div>` : ''}
            <div class="toast-message">${message}</div>
        </div>
        <div class="toast-close">&times;</div>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.remove();
    }, 5000);
    
    // Close button
    toast.querySelector('.toast-close').addEventListener('click', () => {
        toast.remove();
    });
}

// Global loading indicator
function showLoading() {
    let loader = document.getElementById('globalLoader');
    if (!loader) {
        loader = document.createElement('div');
        loader.id = 'globalLoader';
        loader.className = 'global-loader';
        loader.innerHTML = '<div class="spinner"></div>';
        document.body.appendChild(loader);
    }
    loader.style.display = 'flex';
}

function hideLoading() {
    const loader = document.getElementById('globalLoader');
    if (loader) {
        loader.style.display = 'none';
    }
}

// Confirm dialog
function confirmDialog(message, onConfirm, onCancel) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.display = 'flex';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Action</h3>
                <button class="close">&times;</button>
            </div>
            <div class="modal-body">
                <p>${message}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary cancel-btn">Cancel</button>
                <button class="btn btn-primary confirm-btn">Confirm</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    modal.querySelector('.close').addEventListener('click', () => {
        modal.remove();
        if (onCancel) onCancel();
    });
    
    modal.querySelector('.cancel-btn').addEventListener('click', () => {
        modal.remove();
        if (onCancel) onCancel();
    });
    
    modal.querySelector('.confirm-btn').addEventListener('click', () => {
        modal.remove();
        if (onConfirm) onConfirm();
    });
}

// Format date
function formatDate(date, format = 'YYYY-MM-DD HH:mm:ss') {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    const seconds = String(d.getSeconds()).padStart(2, '0');
    
    return format
        .replace('YYYY', year)
        .replace('MM', month)
        .replace('DD', day)
        .replace('HH', hours)
        .replace('mm', minutes)
        .replace('ss', seconds);
}

// Export functions for global use
window.showToast = showToast;
window.showLoading = showLoading;
window.hideLoading = hideLoading;
window.confirmDialog = confirmDialog;
window.formatDate = formatDate;
window.openModal = openModal;
window.closeModal = closeModal;