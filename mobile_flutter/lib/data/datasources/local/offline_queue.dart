import 'package:hive_flutter/hive_flutter.dart';
import '../../models/sync_queue_model.dart';
import '../../../core/constants/storage_keys.dart';
import '../../../core/utils/logger.dart';

class OfflineQueue {
  late Box<SyncQueueModel> _queueBox;

  Future<void> init() async {
    _queueBox = await Hive.openBox<SyncQueueModel>(StorageKeys.offlineBox);
    Logger.info('OfflineQueue initialized');
  }

  Future<void> addToQueue(SyncQueueModel item) async {
    await _queueBox.put(item.id, item);
    Logger.sync('Added to offline queue: ${item.id}');
  }

  Future<List<SyncQueueModel>> getPendingItems() async {
    return _queueBox.values
        .where((item) => item.status == 'pending')
        .toList();
  }

  Future<void> updateItemStatus(String id, String status, {String? error}) async {
    final item = _queueBox.get(id);
    if (item != null) {
      final updatedItem = item.copyWith(
        status: status,
        error: error,
        updatedAt: DateTime.now(),
        retryCount: (item.retryCount ?? 0) + 1,
      );
      await _queueBox.put(id, updatedItem);
    }
  }

  Future<void> removeFromQueue(String id) async {
    await _queueBox.delete(id);
    Logger.sync('Removed from offline queue: $id');
  }

  Future<void> clearQueue() async {
    await _queueBox.clear();
    Logger.sync('Cleared offline queue');
  }

  Future<int> getQueueSize() async {
    return _queueBox.length;
  }
}