import '../../data/repositories/sync_repository.dart';
import '../../core/utils/logger.dart';

class SyncHealthCheck {
  final SyncRepository _syncRepository;

  SyncHealthCheck(this._syncRepository);

  Future<bool> checkHealth() async {
    try {
      final response = await _syncRepository.getSyncStatus();
      return response.isSuccess;
    } catch (e) {
      Logger.error('Sync health check failed', e);
      return false;
    }
  }

  Future<Map<String, dynamic>> getDetailedHealth() async {
    final response = await _syncRepository.getSyncStatus();
    return {
      'isHealthy': response.isSuccess,
      'lastSyncTime': response.data?['last_sync_time'],
      'pendingItems': response.data?['pending_items'] ?? 0,
      'status': response.isSuccess ? 'healthy' : 'unhealthy',
    };
  }
}