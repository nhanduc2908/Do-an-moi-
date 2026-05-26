/**
 * Authentication JavaScript
 */

// Auth initialization
document.addEventListener('DOMContentLoaded', function() {
    initLoginForm();
    initRegisterForm();
    initForgotPassword();
    initResetPassword();
    initMfaSetup();
});

// Login Form
function initLoginForm() {
    const form = document.getElementById('loginForm');
    if (!form) return;
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const remember = document.getElementById('remember')?.checked || false;
        
        if (!email || !password) {
            showToast('Please enter email and password', 'warning');
            return;
        }
        
        showLoading();
        
        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email, password, remember })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Login successful', 'success');
                window.location.href = data.redirect || '/dashboard';
            } else {
                showToast(data.message || 'Invalid credentials', 'danger');
            }
        } catch (error) {
            showToast('Login failed. Please try again.', 'danger');
        } finally {
            hideLoading();
        }
    });
}

// Register Form
function initRegisterForm() {
    const form = document.getElementById('registerForm');
    if (!form) return;
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        if (password !== passwordConfirmation) {
            showToast('Passwords do not match', 'warning');
            return;
        }
        
        showLoading();
        
        try {
            const response = await fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ name, email, password, password_confirmation: passwordConfirmation })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Registration successful! Please login.', 'success');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showValidationErrors(data.errors);
            }
        } catch (error) {
            showToast('Registration failed. Please try again.', 'danger');
        } finally {
            hideLoading();
        }
    });
}

// Forgot Password
function initForgotPassword() {
    const form = document.getElementById('forgotPasswordForm');
    if (!form) return;
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        
        if (!email) {
            showToast('Please enter your email', 'warning');
            return;
        }
        
        showLoading();
        
        try {
            const response = await fetch('/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Reset link sent to your email', 'success');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 3000);
            } else {
                showToast(data.message || 'Email not found', 'danger');
            }
        } catch (error) {
            showToast('Failed to send reset link', 'danger');
        } finally {
            hideLoading();
        }
    });
}

// Reset Password
function initResetPassword() {
    const form = document.getElementById('resetPasswordForm');
    if (!form) return;
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const token = document.getElementById('token').value;
        
        if (password !== passwordConfirmation) {
            showToast('Passwords do not match', 'warning');
            return;
        }
        
        if (password.length < 8) {
            showToast('Password must be at least 8 characters', 'warning');
            return;
        }
        
        showLoading();
        
        try {
            const response = await fetch('/reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ password, password_confirmation: passwordConfirmation, token })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Password reset successful! Please login.', 'success');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showToast(data.message || 'Failed to reset password', 'danger');
            }
        } catch (error) {
            showToast('Failed to reset password', 'danger');
        } finally {
            hideLoading();
        }
    });
}

// MFA Setup
function initMfaSetup() {
    const enableBtn = document.getElementById('enableMfa');
    const disableBtn = document.getElementById('disableMfa');
    const verifyForm = document.getElementById('verifyMfaForm');
    
    if (enableBtn) {
        enableBtn.addEventListener('click', enableMfa);
    }
    
    if (disableBtn) {
        disableBtn.addEventListener('click', disableMfa);
    }
    
    if (verifyForm) {
        verifyForm.addEventListener('submit', verifyMfa);
    }
}

async function enableMfa() {
    showLoading();
    
    try {
        const response = await fetch('/mfa/enable', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMfaSetupModal(data.secret, data.qrCode);
        } else {
            showToast('Failed to enable MFA', 'danger');
        }
    } catch (error) {
        showToast('Failed to enable MFA', 'danger');
    } finally {
        hideLoading();
    }
}

function showMfaSetupModal(secret, qrCode) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.display = 'flex';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Setup Two-Factor Authentication</h3>
                <button class="close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="mfa-setup">
                    <p>Scan the QR code with your authenticator app:</p>
                    <div class="qr-code">${qrCode || 'QR Code Placeholder'}</div>
                    <p>Or enter this code manually:</p>
                    <code class="backup-codes">${secret}</code>
                    <form id="verifyMfaForm">
                        <input type="hidden" name="secret" value="${secret}">
                        <div class="form-group">
                            <label>Verification Code</label>
                            <input type="text" name="code" class="form-control" placeholder="Enter 6-digit code" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Verify & Enable</button>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    modal.querySelector('.close').addEventListener('click', () => modal.remove());
    
    const verifyForm = modal.querySelector('#verifyMfaForm');
    verifyForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        await verifyMfa(e);
        modal.remove();
    });
}

async function verifyMfa(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    showLoading();
    
    try {
        const response = await fetch('/mfa/verify', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('MFA enabled successfully', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('Invalid verification code', 'danger');
        }
    } catch (error) {
        showToast('Failed to verify MFA', 'danger');
    } finally {
        hideLoading();
    }
}

async function disableMfa() {
    confirmDialog('Are you sure you want to disable two-factor authentication?', async () => {
        showLoading();
        
        try {
            const response = await fetch('/mfa/disable', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('MFA disabled successfully', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Failed to disable MFA', 'danger');
            }
        } catch (error) {
            showToast('Failed to disable MFA', 'danger');
        } finally {
            hideLoading();
        }
    });
}