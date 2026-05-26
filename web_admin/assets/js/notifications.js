/**
 * Notifications JavaScript - Quản lý thông báo
 */

class NotificationManager {
    constructor() {
        this.container = null;
        this.notifications = [];
        this.init();
    }
    
    init() {
        this.createContainer();
        this.setupEventSource();
    }
    
    createContainer() {
        if (!document.getElementById('notificationContainer')) {
            this.container = document.createElement('div');
            this.container.id = 'notificationContainer';
            this.container.className = 'notification-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('notificationContainer');
        }
    }
    
    setupEventSource() {
        if (typeof EventSource !== 'undefined') {
            const es = new EventSource('/notifications/stream');
            
            es.onmessage = (event) => {
                const data = JSON.parse(event.data);
                this.addNotification(data);
            };
            
            es.onerror = () => {
                console.error('EventSource failed');
                es.close();
            };
        }
    }
    
    addNotification(notification) {
        const id = Date.now();
        this.notifications.unshift({ id, ...notification, read: false });
        this.renderNotification(id, notification);
        this.updateBadge();
        
        // Show toast
        this.showToast(notification);
        
        // Play sound for high priority
        if (notification.priority === 'high' || notification.priority === 'critical') {
            this.playSound();
        }
    }
    
    renderNotification(id, notification) {
        const notificationEl = document.createElement('div');
        notificationEl.className = `notification notification-${notification.type || 'info'} ${notification.priority === 'critical' ? 'notification-critical' : ''}`;
        notificationEl.dataset.id = id;
        notificationEl.innerHTML = `
            <div class="notification-icon">
                ${this.getIcon(notification.type)}
            </div>
            <div class="notification-content">
                <div class="notification-title">${notification.title}</div>
                <div class="notification-message">${notification.message}</div>
                <div class="notification-time">${this.formatTime(notification.time)}</div>
            </div>
            <button class="notification-close">&times;</button>
        `;
        
        notificationEl.querySelector('.notification-close').addEventListener('click', () => {
            this.removeNotification(id);
        });
        
        this.container.prepend(notificationEl);
        
        // Auto remove after 10 seconds
        setTimeout(() => {
            if (document.querySelector(`.notification[data-id="${id}"]`)) {
                this.removeNotification(id);
            }
        }, 10000);
    }
    
    removeNotification(id) {
        const notification = this.container.querySelector(`.notification[data-id="${id}"]`);
        if (notification) {
            notification.remove();
        }
        this.notifications = this.notifications.filter(n => n.id !== id);
        this.updateBadge();
    }
    
    getIcon(type) {
        const icons = {
            success: '✓',
            error: '✗',
            warning: '⚠',
            info: 'ℹ',
            alert: '🔔'
        };
        return icons[type] || icons.info;
    }
    
    formatTime(time) {
        const date = new Date(time);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return `${Math.floor(diff / 60000)} min ago`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)} hours ago`;
        return date.toLocaleDateString();
    }
    
    showToast(notification) {
        showToast(notification.message, notification.type || 'info', notification.title);
    }
    
    playSound() {
        const audio = new Audio('/sounds/notification.mp3');
        audio.play().catch(e => console.error('Cannot play sound:', e));
    }
    
    updateBadge() {
        const unreadCount = this.notifications.filter(n => !n.read).length;
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }
    
    markAsRead(id) {
        const notification = this.notifications.find(n => n.id === id);
        if (notification) {
            notification.read = true;
            this.updateBadge();
        }
    }
    
    markAllAsRead() {
        this.notifications.forEach(n => n.read = true);
        this.updateBadge();
    }
    
    clearAll() {
        this.container.innerHTML = '';
        this.notifications = [];
        this.updateBadge();
    }
}

// Initialize notification manager
const notificationManager = new NotificationManager();

// Request notification permission
if ('Notification' in window && Notification.permission !== 'granted') {
    Notification.requestPermission();
}

// Show browser notification
function showBrowserNotification(title, body, icon = '/favicon.ico') {
    if (Notification.permission === 'granted') {
        new Notification(title, { body, icon });
    }
}

window.NotificationManager = notificationManager;
window.showBrowserNotification = showBrowserNotification;