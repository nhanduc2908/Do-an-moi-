import 'package:hive_flutter/hive_flutter.dart';
import '../../models/sync_queue_model.dart';
import '../../../core/constants/storage_keys.dart';
import '../../../core/utils/logger.dart';

class SyncDatabase {
  late Box<SyncQueueModel> _syncQueueBox;

  Future<void> init() async {
    Hive.registerAdapter(SyncQueueModelAdapter());
    _syncQueueBox = await Hive.openBox<SyncQueueModel>(StorageKeys.syncQueueBox);
    Logger.info('SyncDatabase initialized');
  }

  Future<void> insertSyncQueue(SyncQueueModel item) async {
    await _syncQueueBox.put(item.id, item);
    Logger.sync('Inserted sync queue item: ${item.id}');
  }

  Future<List<SyncQueueModel>> getPendingSyncItems() async {
    return _syncQueueBox.values
        .where((item) => item.status == 'pending')
        .toList();
  }

  Future<void> updateSyncStatus(String id, String status, {String? error}) async {
    final item = _syncQueueBox.get(id);
    if (item != null) {
      final updatedItem = item.copyWith(
        status: status,
        error: error,
        updatedAt: DateTime.now(),
        retryCount: item.retryCount + 1,
      );
      await _syncQueueBox.put(id, updatedItem);
      Logger.sync('Updated sync queue item: $id -> $status');
    }
  }

  Future<void> deleteSyncQueueItem(String id) async {
    await _syncQueueBox.delete(id);
    Logger.sync('Deleted sync queue item: $id');
  }

  Future<void> clearOldSyncLogs(int days) async {
    final cutoff = DateTime.now().subtract(Duration(days: days));
    final toDelete = _syncQueueBox.values
        .where((item) => item.createdAt.isBefore(cutoff))
        .map((item) => item.id)
        .toList();
    
    for (final id in toDelete) {
      await _syncQueueBox.delete(id);
    }
    
    Logger.sync('Cleared ${toDelete.length} old sync logs');
  }

  Future<List<SyncQueueModel>> getAllSyncLogs() async {
    return _syncQueueBox.values.toList();
  }
}