import '../../data/models/sync_queue_model.dart';
import '../../data/repositories/sync_repository.dart';
import '../../data/datasources/local/offline_queue.dart';
import '../../core/utils/logger.dart';

class BatchSync {
  final SyncRepository _syncRepository;
  final OfflineQueue _offlineQueue;
  static const int batchSize = 50;

  BatchSync(this._syncRepository, this._offlineQueue);

  Future<void> processBatch() async {
    final items = await _offlineQueue.getPendingItems();
    final batches = _splitIntoBatches(items);
    
    for (final batch in batches) {
      await _processBatch(batch);
    }
  }

  List<List<SyncQueueModel>> _splitIntoBatches(List<SyncQueueModel> items) {
    final batches = <List<SyncQueueModel>>[];
    for (var i = 0; i < items.length; i += batchSize) {
      final end = (i + batchSize < items.length) ? i + batchSize : items.length;
      batches.add(items.sublist(i, end));
    }
    return batches;
  }

  Future<void> _processBatch(List<SyncQueueModel> batch) async {
    for (final item in batch) {
      await _offlineQueue.updateItemStatus(item.id!, 'syncing');
      await _syncRepository.syncToFlutter();
      await _offlineQueue.updateItemStatus(item.id!, 'success');
      await _offlineQueue.removeFromQueue(item.id!);
    }
  }
}