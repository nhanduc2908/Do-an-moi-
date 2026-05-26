import 'dart:async';
import '../../data/models/sync_queue_model.dart';
import '../../data/repositories/sync_repository.dart';
import '../../data/datasources/local/offline_queue.dart';
import '../../core/utils/logger.dart';
import '../../core/utils/network_checker.dart';

class SyncEngine {
  final SyncRepository _syncRepository;
  final OfflineQueue _offlineQueue;
  Timer? _syncTimer;
  bool _isSyncing = false;

  SyncEngine(this._syncRepository, this._offlineQueue);

  void startAutoSync({Duration interval = const Duration(minutes: 15)}) {
    _syncTimer?.cancel();
    _syncTimer = Timer.periodic(interval, (timer) async {
      await sync();
    });
    Logger.sync('Auto-sync started');
  }

  void stopAutoSync() {
    _syncTimer?.cancel();
    _syncTimer = null;
    Logger.sync('Auto-sync stopped');
  }

  Future<void> sync() async {
    if (_isSyncing) return;
    
    final isConnected = await NetworkChecker.isConnected();
    if (!isConnected) {
      Logger.sync('No network, sync deferred');
      return;
    }

    _isSyncing = true;
    try {
      await _syncPendingItems();
      await _syncWithServer();
      Logger.sync('Sync completed');
    } catch (e) {
      Logger.error('Sync failed', e);
    } finally {
      _isSyncing = false;
    }
  }

  Future<void> _syncPendingItems() async {
    final items = await _offlineQueue.getPendingItems();
    for (final item in items) {
      await _processQueueItem(item);
    }
  }

  Future<void> _processQueueItem(SyncQueueModel item) async {
    try {
      await _offlineQueue.updateItemStatus(item.id!, 'syncing');
      await _syncRepository.syncToFlutter();
      await _offlineQueue.updateItemStatus(item.id!, 'success');
      await _offlineQueue.removeFromQueue(item.id!);
    } catch (e) {
      await _offlineQueue.updateItemStatus(item.id!, 'failed', error: e.toString());
    }
  }

  Future<void> _syncWithServer() async {
    await _syncRepository.syncToFlutter();
  }

  bool get isSyncing => _isSyncing;
}