/**
 * Forms JavaScript - Xử lý form và validation
 */

class FormValidator {
    constructor(form, rules = {}) {
        this.form = form;
        this.rules = rules;
        this.errors = {};
        this.init();
    }
    
    init() {
        this.attachEvents();
    }
    
    attachEvents() {
        this.form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearFieldError(field));
        });
        
        this.form.addEventListener('submit', (e) => {
            if (!this.validate()) {
                e.preventDefault();
                this.showFirstError();
            }
        });
    }
    
    validate() {
        this.errors = {};
        let isValid = true;
        
        for (const [fieldName, rules] of Object.entries(this.rules)) {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                const fieldError = this.validateFieldRules(field, rules);
                if (fieldError) {
                    this.errors[fieldName] = fieldError;
                    isValid = false;
                }
            }
        }
        
        return isValid;
    }
    
    validateField(field) {
        const fieldName = field.name;
        const rules = this.rules[fieldName];
        
        if (rules) {
            const error = this.validateFieldRules(field, rules);
            if (error) {
                this.showFieldError(field, error);
                return false;
            } else {
                this.clearFieldError(field);
                return true;
            }
        }
        
        return true;
    }
    
    validateFieldRules(field, rules) {
        const value = field.value;
        
        // Required
        if (rules.required && !value) {
            return 'This field is required';
        }
        
        // Min length
        if (rules.minLength && value.length < rules.minLength) {
            return `Minimum length is ${rules.minLength} characters`;
        }
        
        // Max length
        if (rules.maxLength && value.length > rules.maxLength) {
            return `Maximum length is ${rules.maxLength} characters`;
        }
        
        // Email
        if (rules.email && value && !this.isValidEmail(value)) {
            return 'Please enter a valid email address';
        }
        
        // Pattern
        if (rules.pattern && value && !rules.pattern.test(value)) {
            return rules.patternMessage || 'Invalid format';
        }
        
        // Confirm
        if (rules.confirm) {
            const confirmField = this.form.querySelector(`[name="${rules.confirm}"]`);
            if (confirmField && value !== confirmField.value) {
                return 'Values do not match';
            }
        }
        
        return null;
    }
    
    showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        let feedback = field.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.appendChild(feedback);
        }
        feedback.textContent = message;
    }
    
    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }
    
    showFirstError() {
        const firstErrorField = this.form.querySelector('.is-invalid');
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.focus();
        }
    }
    
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
}

// Auto-initialize forms with data-validate attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-validate]').forEach(form => {
        const rulesAttr = form.getAttribute('data-rules');
        if (rulesAttr) {
            try {
                const rules = JSON.parse(rulesAttr);
                new FormValidator(form, rules);
            } catch (e) {
                console.error('Invalid rules JSON:', e);
            }
        }
    });
});

// Form Helpers
function serializeForm(form) {
    const formData = new FormData(form);
    const data = {};
    
    for (const [key, value] of formData.entries()) {
        if (data[key]) {
            if (!Array.isArray(data[key])) {
                data[key] = [data[key]];
            }
            data[key].push(value);
        } else {
            data[key] = value;
        }
    }
    
    return data;
}

function resetForm(form) {
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
}

window.FormValidator = FormValidator;
window.serializeForm = serializeForm;
window.resetForm = resetForm;