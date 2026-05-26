import 'package:hive_flutter/hive_flutter.dart';
import '../../models/sync_metadata_model.dart';
import '../../../core/constants/storage_keys.dart';
import '../../../core/utils/logger.dart';

class SyncMetadataStore {
  late Box<SyncMetadataModel> _metadataBox;

  Future<void> init() async {
    _metadataBox = await Hive.openBox<SyncMetadataModel>(StorageKeys.syncQueueBox);
    Logger.info('SyncMetadataStore initialized');
  }

  Future<void> saveMetadata(SyncMetadataModel metadata) async {
    await _metadataBox.put(metadata.id, metadata);
    Logger.sync('Saved sync metadata: ${metadata.entityType}');
  }

  Future<SyncMetadataModel?> getMetadata(String id) async {
    return _metadataBox.get(id);
  }

  Future<SyncMetadataModel?> getMetadataByEntityType(String entityType) async {
    return _metadataBox.values.firstWhere(
      (item) => item.entityType == entityType,
      orElse: () => throw StateError('Not found'),
    );
  }

  Future<void> updateLastSyncTime(String entityType, DateTime time) async {
    final metadata = await getMetadataByEntityType(entityType);
    if (metadata != null) {
      final updated = metadata.copyWith(
        lastSyncTime: time,
        updatedAt: DateTime.now(),
      );
      await _metadataBox.put(metadata.id, updated);
    } else {
      final newMetadata = SyncMetadataModel(
        id: DateTime.now().millisecondsSinceEpoch.toString(),
        entityType: entityType,
        lastSyncTime: time,
        syncStatus: 'pending',
        version: 1,
        itemsSynced: 0,
        createdAt: DateTime.now(),
        updatedAt: DateTime.now(),
      );
      await _metadataBox.put(newMetadata.id, newMetadata);
    }
  }

  Future<void> updateSyncStatus(String entityType, String status) async {
    final metadata = await getMetadataByEntityType(entityType);
    if (metadata != null) {
      final updated = metadata.copyWith(
        syncStatus: status,
        updatedAt: DateTime.now(),
      );
      await _metadataBox.put(metadata.id, updated);
    }
  }

  Future<List<SyncMetadataModel>> getAllMetadata() async {
    return _metadataBox.values.toList();
  }

  Future<void> deleteMetadata(String id) async {
    await _metadataBox.delete(id);
    Logger.sync('Deleted sync metadata: $id');
  }

  Future<void> clearAll() async {
    await _metadataBox.clear();
    Logger.sync('Cleared all sync metadata');
  }
}