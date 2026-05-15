@extends('admin.layouts.app')

@section('title', 'Security Settings')
@section('page-title', 'Security Configuration')

@section('content')
<form method="POST" action="{{ route('admin.settings.security.update') }}">
    @csrf @method('PUT')
    
    <div class="settings-section">
        <h3>Password Policy</h3>
        
        <div class="form-group">
            <label class="form-label">Minimum Password Length</label>
            <input type="number" name="password_min_length" class="form-control" value="{{ setting('password_min_length', 12) }}" min="8" max="20">
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="password_require_uppercase" value="1" {{ setting('password_require_uppercase', true) ? 'checked' : '' }}>
                Require Uppercase Letter
            </label>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="password_require_numbers" value="1" {{ setting('password_require_numbers', true) ? 'checked' : '' }}>
                Require Numbers
            </label>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="password_require_special" value="1" {{ setting('password_require_special', true) ? 'checked' : '' }}>
                Require Special Characters
            </label>
        </div>
        
        <div class="form-group">
            <label class="form-label">Password Expiry (days)</label>
            <input type="number" name="password_expiry_days" class="form-control" value="{{ setting('password_expiry_days', 90) }}" min="0" max="365">
            <small>0 = Never expires</small>
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Session Security</h3>
        
        <div class="form-group">
            <label class="form-label">Session Lifetime (minutes)</label>
            <input type="number" name="session_lifetime" class="form-control" value="{{ setting('session_lifetime', 120) }}" min="15" max="1440">
        </div>
        
        <div class="form-group">
            <label class="form-label">Idle Timeout (minutes)</label>
            <input type="number" name="session_idle_timeout" class="form-control" value="{{ setting('session_idle_timeout', 30) }}" min="5" max="120">
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="session_encrypt" value="1" {{ setting('session_encrypt', true) ? 'checked' : '' }}>
                Encrypt Session Data
            </label>
        </div>
    </div>
    
    <div class="settings-section">
        <h3>MFA Configuration</h3>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="mfa_enabled" value="1" {{ setting('mfa_enabled', true) ? 'checked' : '' }}>
                Enable Two-Factor Authentication
            </label>
        </div>
        
        <div class="form-group">
            <label class="form-label">MFA Methods</label>
            <div class="checkbox-group">
                <label><input type="checkbox" name="mfa_methods[]" value="authenticator" {{ in_array('authenticator', setting('mfa_methods', [])) ? 'checked' : '' }}> Authenticator App</label>
                <label><input type="checkbox" name="mfa_methods[]" value="sms" {{ in_array('sms', setting('mfa_methods', [])) ? 'checked' : '' }}> SMS</label>
                <label><input type="checkbox" name="mfa_methods[]" value="email" {{ in_array('email', setting('mfa_methods', [])) ? 'checked' : '' }}> Email</label>
            </div>
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Rate Limiting</h3>
        
        <div class="form-group">
            <label class="form-label">API Rate Limit (requests per minute)</label>
            <input type="number" name="rate_limit_api" class="form-control" value="{{ setting('rate_limit_api', 60) }}" min="10" max="1000">
        </div>
        
        <div class="form-group">
            <label class="form-label">Login Attempts (before lockout)</label>
            <input type="number" name="rate_limit_login" class="form-control" value="{{ setting('rate_limit_login', 5) }}" min="3" max="20">
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Security Settings</button>
    </div>
</form>
@endsection