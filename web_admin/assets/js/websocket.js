/**
 * WebSocket JavaScript - Kết nối real-time
 */

class WebSocketManager {
    constructor(url, options = {}) {
        this.url = url;
        this.options = {
            reconnectInterval: options.reconnectInterval || 3000,
            maxReconnectAttempts: options.maxReconnectAttempts || 10,
            onOpen: options.onOpen || null,
            onMessage: options.onMessage || null,
            onClose: options.onClose || null,
            onError: options.onError || null
        };
        
        this.ws = null;
        this.reconnectAttempts = 0;
        this.isConnected = false;
        this.messageQueue = [];
        
        this.connect();
    }
    
    connect() {
        try {
            this.ws = new WebSocket(this.url);
            
            this.ws.onopen = (event) => {
                this.isConnected = true;
                this.reconnectAttempts = 0;
                this.flushMessageQueue();
                if (this.options.onOpen) this.options.onOpen(event);
                console.log('WebSocket connected');
            };
            
            this.ws.onmessage = (event) => {
                const data = JSON.parse(event.data);
                if (this.options.onMessage) this.options.onMessage(data);
                this.handleMessage(data);
            };
            
            this.ws.onclose = (event) => {
                this.isConnected = false;
                if (this.options.onClose) this.options.onClose(event);
                this.reconnect();
            };
            
            this.ws.onerror = (error) => {
                console.error('WebSocket error:', error);
                if (this.options.onError) this.options.onError(error);
            };
            
        } catch (error) {
            console.error('WebSocket connection failed:', error);
            this.reconnect();
        }
    }
    
    reconnect() {
        if (this.reconnectAttempts >= this.options.maxReconnectAttempts) {
            console.error('Max reconnection attempts reached');
            return;
        }
        
        this.reconnectAttempts++;
        console.log(`Reconnecting... Attempt ${this.reconnectAttempts}`);
        
        setTimeout(() => {
            this.connect();
        }, this.options.reconnectInterval);
    }
    
    send(data) {
        if (this.isConnected) {
            this.ws.send(JSON.stringify(data));
        } else {
            this.messageQueue.push(data);
        }
    }
    
    flushMessageQueue() {
        while (this.messageQueue.length > 0) {
            const data = this.messageQueue.shift();
            this.send(data);
        }
    }
    
    handleMessage(data) {
        switch (data.type) {
            case 'incident':
                this.handleIncident(data);
                break;
            case 'assessment':
                this.handleAssessment(data);
                break;
            case 'alert':
                this.handleAlert(data);
                break;
            case 'sync':
                this.handleSync(data);
                break;
            default:
                console.log('Unknown message type:', data.type);
        }
    }
    
    handleIncident(data) {
        showToast(`New incident: ${data.title}`, data.severity === 'critical' ? 'danger' : 'warning');
        this.updateIncidentCount(data);
    }
    
    handleAssessment(data) {
        showToast(`Assessment completed: ${data.title}`, 'info');
        if (window.location.pathname.includes('/dashboard')) {
            location.reload();
        }
    }
    
    handleAlert(data) {
        showToast(data.message, data.severity || 'info', data.title);
        if (data.severity === 'critical') {
            showBrowserNotification(data.title, data.message);
        }
    }
    
    handleSync(data) {
        console.log('Sync completed:', data);
    }
    
    updateIncidentCount(data) {
        const badge = document.getElementById('incidentCount');
        if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            badge.textContent = currentCount + 1;
        }
    }
    
    disconnect() {
        if (this.ws) {
            this.ws.close();
        }
    }
    
    subscribe(channel) {
        this.send({ action: 'subscribe', channel: channel });
    }
    
    unsubscribe(channel) {
        this.send({ action: 'unsubscribe', channel: channel });
    }
}

// Initialize WebSocket connection
let wsManager = null;

function initWebSocket() {
    const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const wsUrl = `${protocol}//${window.location.host}/ws`;
    
    wsManager = new WebSocketManager(wsUrl, {
        onOpen: () => {
            console.log('WebSocket connected');
            wsManager.subscribe('incidents');
            wsManager.subscribe('alerts');
            wsManager.subscribe('assessments');
        },
        onMessage: (data) => {
            console.log('Received:', data);
        }
    });
}

// Auto-initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initWebSocket();
});

window.WebSocketManager = WebSocketManager;
window.wsManager = wsManager;