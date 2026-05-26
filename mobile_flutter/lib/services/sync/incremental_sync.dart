import '../../data/repositories/sync_repository.dart';
import '../../data/datasources/local/sync_metadata_store.dart';
import '../../core/utils/logger.dart';

class IncrementalSync {
  final SyncRepository _syncRepository;
  final SyncMetadataStore _metadataStore;

  IncrementalSync(this._syncRepository, this._metadataStore);

  Future<void> sync() async {
    final lastSyncTime = await _getLastSyncTime();
    Logger.sync('Incremental sync from: $lastSyncTime');
    
    await _syncRepository.syncToFlutter(lastSyncTime: lastSyncTime?.toIso8601String());
    await _updateLastSyncTime();
  }

  Future<DateTime?> _getLastSyncTime() async {
    final metadata = await _metadataStore.getMetadata('incremental_sync');
    return metadata?.lastSyncTime;
  }

  Future<void> _updateLastSyncTime() async {
    await _metadataStore.updateLastSyncTime('incremental_sync', DateTime.now());
  }
}