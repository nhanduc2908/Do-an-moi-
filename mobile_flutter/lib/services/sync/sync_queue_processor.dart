import '../../data/models/sync_queue_model.dart';
import '../../data/datasources/local/offline_queue.dart';
import '../../core/utils/logger.dart';

class SyncQueueProcessor {
  final OfflineQueue _offlineQueue;
  bool _isProcessing = false;

  SyncQueueProcessor(this._offlineQueue);

  Future<void> startProcessing() async {
    if (_isProcessing) return;
    _isProcessing = true;
    
    while (_isProcessing) {
      await _processNextItem();
      await Future.delayed(const Duration(seconds: 2));
    }
  }

  void stopProcessing() {
    _isProcessing = false;
  }

  Future<void> _processNextItem() async {
    final items = await _offlineQueue.getPendingItems();
    if (items.isEmpty) return;

    final item = items.first;
    await _offlineQueue.updateItemStatus(item.id!, 'processing');
    
    try {
      // Process item logic here
      await _offlineQueue.updateItemStatus(item.id!, 'success');
      await _offlineQueue.removeFromQueue(item.id!);
    } catch (e) {
      await _offlineQueue.updateItemStatus(item.id!, 'failed', error: e.toString());
    }
  }
}