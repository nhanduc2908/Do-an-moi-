@extends('admin.layouts.app')

@section('title', 'AI Security Assistant')
@section('page-title', 'AI Security Chatbot')

@section('content')
<div class="chat-container">
    <div class="chat-sidebar">
        <h4>Conversations</h4>
        <div class="chat-history">
            @foreach($conversations as $conv)
            <div class="history-item" data-session="{{ $conv->session_id }}">
                <div class="history-title">{{ Str::limit($conv->message, 40) }}</div>
                <div class="history-time">{{ $conv->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
        </div>
        <button id="newChatBtn" class="btn btn-primary w-100">+ New Conversation</button>
    </div>
    
    <div class="chat-main">
        <div class="chat-messages" id="chatMessages">
            <div class="message assistant">
                <div class="message-avatar">🤖</div>
                <div class="message-content">
                    Hello! I'm your AI Security Assistant. I can help you with:
                    <ul>
                        <li>Vulnerability scanning and analysis</li>
                        <li>Compliance checks (ISO 27001, GDPR, PCI DSS)</li>
                        <li>Incident response guidance</li>
                        <li>Security report generation</li>
                        <li>Risk assessment</li>
                    </ul>
                    How can I help you today?
                </div>
            </div>
        </div>
        
        <div class="chat-input-area">
            <textarea id="chatInput" class="form-control" rows="3" placeholder="Type your message here..."></textarea>
            <div class="chat-actions">
                <button id="sendBtn" class="btn btn-primary">Send</button>
                <button id="clearBtn" class="btn btn-secondary">Clear</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chat-container {
        display: flex;
        height: calc(100vh - 200px);
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }
    .chat-sidebar {
        width: 280px;
        border-right: 1px solid #e2e8f0;
        padding: 1rem;
        overflow-y: auto;
    }
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
    }
    .message {
        display: flex;
        margin-bottom: 1rem;
        gap: 0.75rem;
    }
    .message.user {
        flex-direction: row-reverse;
    }
    .message-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #1a56db;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .message.user .message-avatar {
        background: #10b981;
    }
    .message-content {
        max-width: 70%;
        padding: 0.75rem 1rem;
        background: #f3f4f6;
        border-radius: 12px;
    }
    .message.user .message-content {
        background: #1a56db;
        color: white;
    }
    .chat-input-area {
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
    }
    .chat-actions {
        margin-top: 0.5rem;
        display: flex;
        gap: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentSession = '{{ session()->getId() }}';
    
    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    document.getElementById('chatInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;
        
        addMessage(message, 'user');
        input.value = '';
        
        const response = await fetch('{{ route("admin.ai.chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message,
                session_id: currentSession
            })
        });
        
        const data = await response.json();
        addMessage(data.response, 'assistant');
        
        if (data.intent === 'vulnerability_scan') {
            startVulnerabilityScan();
        }
    }
    
    function addMessage(text, sender) {
        const messagesDiv = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        messageDiv.innerHTML = `
            <div class="message-avatar">${sender === 'user' ? '👤' : '🤖'}</div>
            <div class="message-content">${text}</div>
        `;
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
    
    async function startVulnerabilityScan() {
        addMessage('Starting vulnerability scan. This may take a few minutes...', 'assistant');
        
        const response = await fetch('{{ route("admin.ai.scan.trigger") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        const data = await response.json();
        if (data.success) {
            addMessage('Scan completed! Found ' + data.vulnerabilities + ' vulnerabilities.', 'assistant');
        }
    }
    
    document.getElementById('clearBtn').addEventListener('click', function() {
        const messagesDiv = document.getElementById('chatMessages');
        messagesDiv.innerHTML = `
            <div class="message assistant">
                <div class="message-avatar">🤖</div>
                <div class="message-content">Conversation cleared. How can I help you?</div>
            </div>
        `;
    });
    
    document.querySelectorAll('.history-item').forEach(item => {
        item.addEventListener('click', function() {
            currentSession = this.dataset.session;
            loadConversation(currentSession);
        });
    });
    
    async function loadConversation(sessionId) {
        const response = await fetch(`/admin/ai/chat/history/${sessionId}`);
        const messages = await response.json();
        
        const messagesDiv = document.getElementById('chatMessages');
        messagesDiv.innerHTML = '';
        
        messages.forEach(msg => {
            addMessage(msg.message, 'user');
            addMessage(msg.response, 'assistant');
        });
    }
</script>
@endpush
@endsection