/**
 * Sync JavaScript - Đồng bộ dữ liệu
 */

class SyncManager {
    constructor() {
        this.isSyncing = false;
        this.lastSyncTime = null;
        this.syncInterval = null;
        this.pendingItems = 0;
        this.init();
    }
    
    init() {
        this.loadSyncStatus();
        this.startAutoSync();
        this.setupEventListeners();
    }
    
    loadSyncStatus() {
        fetch('/api/sync/status')
            .then(response => response.json())
            .then(data => {
                this.lastSyncTime = data.last_sync_time ? new Date(data.last_sync_time) : null;
                this.pendingItems = data.pending_items || 0;
                this.updateUI();
            })
            .catch(error => console.error('Failed to load sync status:', error));
    }
    
    startAutoSync() {
        this.syncInterval = setInterval(() => {
            if (!this.isSyncing) {
                this.autoSync();
            }
        }, 300000); // 5 minutes
    }
    
    stopAutoSync() {
        if (this.syncInterval) {
            clearInterval(this.syncInterval);
            this.syncInterval = null;
        }
    }
    
    async autoSync() {
        if (this.pendingItems === 0) return;
        
        await this.sync();
    }
    
    async sync() {
        if (this.isSyncing) {
            showToast('Sync already in progress', 'warning');
            return;
        }
        
        this.isSyncing = true;
        this.updateUI();
        
        try {
            const response = await fetch('/api/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.lastSyncTime = new Date();
                this.pendingItems = 0;
                showToast('Sync completed successfully', 'success');
                this.updateUI();
                this.triggerRefresh();
            } else {
                throw new Error(data.message || 'Sync failed');
            }
        } catch (error) {
            console.error('Sync error:', error);
            showToast('Sync failed', 'danger');
        } finally {
            this.isSyncing = false;
            this.updateUI();
        }
    }
    
    async syncEntity(entityType, id = null) {
        if (this.isSyncing) return;
        
        this.isSyncing = true;
        
        try {
            const url = id ? `/api/sync/${entityType}/${id}` : `/api/sync/${entityType}`;
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast(`${entityType} synced successfully`, 'success');
                return true;
            } else {
                throw new Error(data.message || 'Sync failed');
            }
        } catch (error) {
            console.error('Sync error:', error);
            showToast(`Failed to sync ${entityType}`, 'danger');
            return false;
        } finally {
            this.isSyncing = false;
        }
    }
    
    triggerRefresh() {
        // Refresh current page data
        if (window.location.pathname.includes('/dashboard')) {
            location.reload();
        } else {
            // Trigger data refresh via custom event
            document.dispatchEvent(new CustomEvent('data-synced'));
        }
    }
    
    updateUI() {
        const syncStatus = document.getElementById('syncStatus');
        const lastSyncTime = document.getElementById('lastSyncTime');
        const pendingBadge = document.getElementById('pendingSyncBadge');
        const syncBtn = document.getElementById('syncBtn');
        
        if (syncStatus) {
            syncStatus.innerHTML = this.isSyncing ? 
                '<span class="sync-spinner"></span> Syncing...' : 
                '<i class="fas fa-check-circle"></i> Ready';
        }
        
        if (lastSyncTime) {
            lastSyncTime.textContent = this.lastSyncTime ? 
                this.formatTime(this.lastSyncTime) : 'Never';
        }
        
        if (pendingBadge) {
            if (this.pendingItems > 0) {
                pendingBadge.textContent = this.pendingItems;
                pendingBadge.style.display = 'inline-block';
            } else {
                pendingBadge.style.display = 'none';
            }
        }
        
        if (syncBtn) {
            syncBtn.disabled = this.isSyncing;
        }
    }
    
    formatTime(date) {
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return `${Math.floor(diff / 60000)} minutes ago`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)} hours ago`;
        return date.toLocaleString();
    }
    
    setupEventListeners() {
        const syncBtn = document.getElementById('syncBtn');
        if (syncBtn) {
            syncBtn.addEventListener('click', () => this.sync());
        }
        
        // Listen for online/offline events
        window.addEventListener('online', () => {
            showToast('Network restored. Syncing...', 'success');
            this.sync();
        });
        
        window.addEventListener('offline', () => {
            showToast('Network disconnected. Changes will be saved locally.', 'warning');
        });
    }
}

// Initialize sync manager
const syncManager = new SyncManager();

window.SyncManager = syncManager;