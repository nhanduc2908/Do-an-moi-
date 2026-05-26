/**
 * Validation JavaScript - Xác thực dữ liệu
 */

// Validation rules
const ValidationRules = {
    required: (value) => value !== null && value !== undefined && value.toString().trim() !== '',
    email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
    phone: (value) => /^[0-9]{10,11}$/.test(value),
    minLength: (value, length) => value && value.length >= length,
    maxLength: (value, length) => !value || value.length <= length,
    numeric: (value) => !isNaN(parseFloat(value)) && isFinite(value),
    integer: (value) => Number.isInteger(Number(value)),
    min: (value, min) => parseFloat(value) >= min,
    max: (value, max) => parseFloat(value) <= max,
    url: (value) => /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/.test(value),
    ip: (value) => /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(value),
    date: (value) => !isNaN(Date.parse(value)),
    password: (value) => {
        const hasUpper = /[A-Z]/.test(value);
        const hasLower = /[a-z]/.test(value);
        const hasNumber = /[0-9]/.test(value);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(value);
        return value.length >= 8 && hasUpper && hasLower && hasNumber && hasSpecial;
    }
};

// Validation messages
const ValidationMessages = {
    required: 'This field is required',
    email: 'Please enter a valid email address',
    phone: 'Please enter a valid phone number (10-11 digits)',
    minLength: (length) => `Minimum length is ${length} characters`,
    maxLength: (length) => `Maximum length is ${length} characters`,
    numeric: 'Please enter a valid number',
    integer: 'Please enter a valid integer',
    min: (min) => `Value must be at least ${min}`,
    max: (max) => `Value must not exceed ${max}`,
    url: 'Please enter a valid URL',
    ip: 'Please enter a valid IP address',
    date: 'Please enter a valid date',
    password: 'Password must be at least 8 characters with uppercase, lowercase, number and special character'
};

// Validator class
class Validator {
    constructor() {
        this.rules = ValidationRules;
        this.messages = ValidationMessages;
    }
    
    validate(value, rules) {
        const errors = [];
        
        for (const rule of rules) {
            const [ruleName, ruleValue] = typeof rule === 'string' ? [rule, null] : Object.entries(rule)[0];
            const isValid = this.applyRule(value, ruleName, ruleValue);
            
            if (!isValid) {
                errors.push(this.getErrorMessage(ruleName, ruleValue));
            }
        }
        
        return {
            valid: errors.length === 0,
            errors: errors
        };
    }
    
    applyRule(value, ruleName, ruleValue) {
        const rule = this.rules[ruleName];
        if (!rule) return true;
        
        if (ruleValue !== null && ruleValue !== undefined) {
            return rule(value, ruleValue);
        }
        return rule(value);
    }
    
    getErrorMessage(ruleName, ruleValue) {
        const message = this.messages[ruleName];
        if (typeof message === 'function') {
            return message(ruleValue);
        }
        return message || `Validation failed for rule: ${ruleName}`;
    }
    
    validateForm(form, fields) {
        const errors = {};
        let isValid = true;
        
        for (const [fieldName, rules] of Object.entries(fields)) {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                const result = this.validate(field.value, rules);
                if (!result.valid) {
                    errors[fieldName] = result.errors;
                    isValid = false;
                    this.showFieldError(field, result.errors[0]);
                } else {
                    this.clearFieldError(field);
                }
            }
        }
        
        return { isValid, errors };
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
    
    validatePasswordStrength(password) {
        let score = 0;
        const feedback = [];
        
        if (password.length >= 8) {
            score += 10;
        } else {
            feedback.push('Password should be at least 8 characters');
        }
        
        if (password.length >= 12) score += 10;
        if (/[A-Z]/.test(password)) score += 15;
        if (/[a-z]/.test(password)) score += 10;
        if (/[0-9]/.test(password)) score += 15;
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score += 20;
        
        let strength = 'Very Weak';
        let color = '#dc3545';
        
        if (score >= 80) { strength = 'Very Strong'; color = '#006400'; }
        else if (score >= 70) { strength = 'Strong'; color = '#28a745'; }
        else if (score >= 50) { strength = 'Fair'; color = '#ffc107'; }
        else if (score >= 30) { strength = 'Weak'; color = '#fd7e14'; }
        
        return { score, strength, color, feedback };
    }
}

// Initialize validator
const validator = new Validator();

// Auto-validate forms
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-validate-form]').forEach(form => {
        const fieldsAttr = form.getAttribute('data-fields');
        if (fieldsAttr) {
            try {
                const fields = JSON.parse(fieldsAttr);
                form.addEventListener('submit', (e) => {
                    const result = validator.validateForm(form, fields);
                    if (!result.isValid) {
                        e.preventDefault();
                        showToast('Please fix the errors in the form', 'danger');
                    }
                });
            } catch (e) {
                console.error('Invalid fields JSON:', e);
            }
        }
    });
    
    // Password strength meter
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', () => {
            const result = validator.validatePasswordStrength(passwordInput.value);
            const meter = document.getElementById('passwordStrengthMeter');
            const text = document.getElementById('passwordStrengthText');
            
            if (meter && text) {
                meter.style.width = `${result.score}%`;
                meter.style.backgroundColor = result.color;
                text.textContent = result.strength;
                text.style.color = result.color;
            }
        });
    }
});

window.Validator = validator;