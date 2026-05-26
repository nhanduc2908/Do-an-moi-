import '../../data/models/sync_queue_model.dart';
import '../../data/datasources/local/offline_queue.dart';
import '../../core/utils/logger.dart';

class OfflineQueueManager {
  final OfflineQueue _offlineQueue;

  OfflineQueueManager(this._offlineQueue);

  Future<void> addToQueue(SyncQueueModel item) async {
    await _offlineQueue.addToQueue(item);
    Logger.offline('Added to queue: ${item.endpoint}');
  }

  Future<List<SyncQueueModel>> getPendingItems() async {
    return await _offlineQueue.getPendingItems();
  }

  Future<void> retryFailedItems() async {
    final items = await _offlineQueue.getPendingItems();
    for (final item in items) {
      if (item.isFailed && item.canRetry) {
        await _offlineQueue.updateItemStatus(item.id!, 'pending');
      }
    }
  }

  Future<int> getQueueSize() async {
    return await _offlineQueue.getQueueSize();
  }

  Future<void> clearQueue() async {
    await _offlineQueue.clearQueue();
  }
}