/**
 * AJAX JavaScript - Xử lý các request AJAX
 */

class Ajax {
    static async get(url, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const fullUrl = queryString ? `${url}?${queryString}` : url;
        
        return this.request(fullUrl, { method: 'GET' });
    }
    
    static async post(url, data = {}) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }
    
    static async put(url, data = {}) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }
    
    static async delete(url) {
        return this.request(url, { method: 'DELETE' });
    }
    
    static async request(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        };
        
        const mergedOptions = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(url, mergedOptions);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }
            
            return data;
        } catch (error) {
            console.error('AJAX Error:', error);
            throw error;
        }
    }
    
    static async loadContent(url, container, params = {}) {
        const element = typeof container === 'string' ? document.querySelector(container) : container;
        if (!element) return;
        
        showLoading();
        
        try {
            const html = await this.get(url, params);
            element.innerHTML = html;
        } catch (error) {
            element.innerHTML = '<div class="alert alert-danger">Failed to load content</div>';
        } finally {
            hideLoading();
        }
    }
    
    static async submitForm(form, options = {}) {
        const formElement = typeof form === 'string' ? document.querySelector(form) : form;
        if (!formElement) return;
        
        const formData = new FormData(formElement);
        const url = formElement.action;
        const method = formElement.method || 'POST';
        
        showLoading();
        
        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                if (options.onSuccess) options.onSuccess(data);
                showToast(data.message || 'Success', 'success');
                if (options.reset) formElement.reset();
                if (options.redirect) window.location.href = options.redirect;
            } else {
                if (options.onError) options.onError(data);
                showToast(data.message || 'Error', 'danger');
                if (options.showErrors && data.errors) {
                    this.showValidationErrors(formElement, data.errors);
                }
            }
        } catch (error) {
            showToast('Request failed', 'danger');
            if (options.onError) options.onError(error);
        } finally {
            hideLoading();
        }
    }
    
    static showValidationErrors(form, errors) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        
        for (const [field, messages] of Object.entries(errors)) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = Array.isArray(messages) ? messages.join(', ') : messages;
                input.parentNode.appendChild(errorDiv);
            }
        }
    }
    
    static async loadTable(url, tableId, params = {}) {
        try {
            const data = await this.get(url, params);
            const table = document.getElementById(tableId);
            if (table && data.data) {
                this.renderTable(table, data.data);
                if (data.pagination) {
                    this.renderPagination(data.pagination, tableId);
                }
            }
        } catch (error) {
            console.error('Failed to load table:', error);
        }
    }
    
    static renderTable(table, data) {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;
        
        tbody.innerHTML = data.map(row => `
            <tr>
                ${Object.values(row).map(value => `<td>${this.escapeHtml(value)}</td>`).join('')}
                <td class="action-buttons">
                    <button class="btn-edit" onclick="editItem('${row.id}')">Edit</button>
                    <button class="btn-delete" onclick="deleteItem('${row.id}')">Delete</button>
                </td>
            </tr>
        `).join('');
    }
    
    static renderPagination(pagination, tableId) {
        const container = document.getElementById(`${tableId}_pagination`);
        if (!container) return;
        
        let html = '<div class="pagination">';
        
        if (pagination.current_page > 1) {
            html += `<button class="pagination-prev" data-page="${pagination.current_page - 1}">&laquo;</button>`;
        }
        
        for (let i = 1; i <= pagination.last_page; i++) {
            html += `<button class="pagination-item ${i === pagination.current_page ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }
        
        if (pagination.current_page < pagination.last_page) {
            html += `<button class="pagination-next" data-page="${pagination.current_page + 1}">&raquo;</button>`;
        }
        
        html += '</div>';
        container.innerHTML = html;
        
        container.querySelectorAll('[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                const page = btn.dataset.page;
                this.loadTable(window.location.pathname, tableId, { page });
            });
        });
    }
    
    static escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Auto-bind AJAX forms
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-ajax-form]').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            Ajax.submitForm(form, {
                onSuccess: (data) => {
                    if (form.dataset.ajaxRedirect) {
                        window.location.href = form.dataset.ajaxRedirect;
                    }
                    if (form.dataset.ajaxReload) {
                        location.reload();
                    }
                }
            });
        });
    });
    
    document.querySelectorAll('[data-ajax-link]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const url = link.getAttribute('href');
            const target = link.getAttribute('data-ajax-target');
            if (target) {
                Ajax.loadContent(url, target);
            }
        });
    });
});

window.Ajax = Ajax;