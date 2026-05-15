@extends('admin.layouts.app')

@section('title', 'Notification Settings')
@section('page-title', 'Email & Alert Configuration')

@section('content')
<form method="POST" action="{{ route('admin.settings.notification.update') }}">
    @csrf @method('PUT')
    
    <div class="settings-section">
        <h3>Email Configuration</h3>
        
        <div class="form-group">
            <label class="form-label">SMTP Host</label>
            <input type="text" name="mail_host" class="form-control" value="{{ setting('mail_host') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">SMTP Port</label>
            <input type="number" name="mail_port" class="form-control" value="{{ setting('mail_port', 587) }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">SMTP Username</label>
            <input type="text" name="mail_username" class="form-control" value="{{ setting('mail_username') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">SMTP Password</label>
            <input type="password" name="mail_password" class="form-control">
            <small>Leave blank to keep current password</small>
        </div>
        
        <div class="form-group">
            <label class="form-label">From Email Address</label>
            <input type="email" name="mail_from_address" class="form-control" value="{{ setting('mail_from_address', 'security@platform.com') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">From Name</label>
            <input type="text" name="mail_from_name" class="form-control" value="{{ setting('mail_from_name', 'Security Platform') }}">
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Alert Recipients</h3>
        
        <div class="form-group">
            <label class="form-label">Critical Alerts (comma separated)</label>
            <input type="text" name="alert_recipients_critical" class="form-control" value="{{ setting('alert_recipients_critical') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">High Alerts</label>
            <input type="text" name="alert_recipients_high" class="form-control" value="{{ setting('alert_recipients_high') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">Daily Digest Recipients</label>
            <input type="text" name="digest_recipients" class="form-control" value="{{ setting('digest_recipients') }}">
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Alert Channels</h3>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="alert_email" value="1" {{ setting('alert_email', true) ? 'checked' : '' }}>
                Email Alerts
            </label>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="alert_slack" value="1" {{ setting('alert_slack') ? 'checked' : '' }}>
                Slack Alerts
            </label>
        </div>
        
        <div class="form-group" id="slackWebhookGroup" style="display: none;">
            <label class="form-label">Slack Webhook URL</label>
            <input type="url" name="slack_webhook" class="form-control" value="{{ setting('slack_webhook') }}">
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="alert_sms" value="1" {{ setting('alert_sms') ? 'checked' : '' }}>
                SMS Alerts
            </label>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Notification Settings</button>
        <button type="button" id="testEmailBtn" class="btn btn-secondary">Send Test Email</button>
    </div>
</form>

@push('scripts')
<script>
    document.getElementById('testEmailBtn').addEventListener('click', async function() {
        const response = await fetch('{{ route("admin.settings.notification.test") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        if (response.ok) {
            alert('Test email sent successfully!');
        } else {
            alert('Failed to send test email');
        }
    });
    
    document.querySelector('input[name="alert_slack"]').addEventListener('change', function() {
        document.getElementById('slackWebhookGroup').style.display = this.checked ? 'block' : 'none';
    });
    
    if (document.querySelector('input[name="alert_slack"]').checked) {
        document.getElementById('slackWebhookGroup').style.display = 'block';
    }
</script>
@endpush
@endsection